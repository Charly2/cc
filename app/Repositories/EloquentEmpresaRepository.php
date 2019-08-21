<?php

namespace App\Repositories;

use App\ConfigEmpresa;
use App\Empresa;
use DB;
use File;

class EloquentEmpresaRepository
{
    public function all()
    {
        return Empresa::all();
    }
    public function find($id)
    {
        return Empresa::find($id);
    }
    public function findOrFail($id)
    {
        return Empresa::findOrFail($id);
    }
    public function update(array $data, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->rfc = $data['rfc'];
        $empresa->nombre = $data['nombre'];

        return $empresa->save();
    }

    public function create(array $data)
    {
        $empresa                   = new Empresa();
        $empresa->rfc              = $data['rfc'];
        $empresa->nombre           = $data['nombre'];
        $empresa->save();

        $cfgEmpresa                = new ConfigEmpresa;
        $cfgEmpresa->empresa_id    = $empresa->id;
        $cfgEmpresa->configuracion = 'folder_storage';
        $cfgEmpresa->value         = $data['rfc'];

        $path = storage_path().'/app/' . $data['rfc'];
        File::makeDirectory($path, $mode = 0777, true, true);

        return $cfgEmpresa->save();
    }

    public function destroy($id)
    {
        $config = ConfigEmpresa::where('empresa_id', $id)->delete();
        if($config){
            return Empresa::findOrFail($id)->delete();
        }
    }

    public function empresasByPermission(){
        $usertype_id = auth()->user()->usertype_id;
        $id_permiso  = auth()->user()->permiso_id;
        $id          = auth()->user()->id;


        //si es administrador permitir el acceso a todas las empresas
        if ($usertype_id=='1') {
            $empresas = Empresa::All();
            //en caso que el tipo de usuario sea agente Aduanal
        } elseif ($usertype_id=='2') {
            $empresas = DB::select("
                select * from empresas 
                INNER JOIN (
                select  agente_aduanal ,empresa_id
                from expedientes
                GROUP BY agente_aduanal ,empresa_id
                ) as tbl
                on tbl.empresa_id = empresas.id
                where tbl.agente_aduanal=" . $id_permiso
            );
            //en caso que el tipo de usuario sea Empresa
            /*} elseif ($usertype_id=='3'){
                $empresas = DB::select("
                    select * from empresas
                    INNER JOIN (
                    select  agente_aduanal ,empresa_id
                    from expedientes
                    GROUP BY agente_aduanal ,empresa_id
                    ) as tbl
                    on tbl.empresa_id = empresas.id
                    where tbl.empresa_id=".$id_permiso
                );*/
        }elseif ($usertype_id=='3'){
            $empresas = DB::select("
                select * from empresas
                where id = ".$id_permiso
            );
        }else{
            $empresas =array();
        }

        return $empresas;
    }


}
