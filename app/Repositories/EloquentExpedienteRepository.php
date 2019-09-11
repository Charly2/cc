<?php

namespace App\Repositories;

use App\Expediente;
use App\Movimiento;
use DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EloquentExpedienteRepository
 * @package App\Repositories\Expediente
 */
class EloquentExpedienteRepository
{
    /**
     * @param int $id
     * @return Builder
     */
    public function getExpedientesByEmpresaId(int $id) : Builder
    {
        return Expediente::with('empresa','aduana')
            ->where('empresa_id',$id);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data) : bool
    {
        try {
            $expediente = new Expediente;
             $expediente->expediente     = $data['expediente'];
             $expediente->agente_aduanal = $data['agente_aduanal'];
             //$expediente->aduana_id    = $data['aduana'];
             $expediente->descripcion    = $data['descripcion'];
             $expediente->empresa_id     = $data['empresa_id'];
             $expediente->status         = $data['status'];
            $expediente->save();

            return true;
        }catch(\PDOException $ex){

            return false;
        }
    }

    /**
     * @param int $id
     * @return Expediente
     */
    public function getExpedienteById( $id)
    {

        //return DB::select("select e.* , a.nombre as nombre_agente from expedientes e left join agencias a on e.agente_aduanal=a.id where e.id = 5")[0];

        return Expediente::where('expedientes.id',$id)
        ->leftJoin('agencias', 'expedientes.agente_aduanal', 'agencias.id')
        ->select('expedientes.*','agencias.nombre as nombre_agente')->first();
    }

    /**
     * @return int
     */
    public function getLastId() : int
    {
        $expediente = Expediente::orderBy('id', 'desc')->first();
        if(!isset($expediente->id)){
            return 0;
        }

        return $expediente->id;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data, int $id) : bool
    {
        try {
            $expediente = Expediente::findOrFail($id);
            //$expediente->agente_aduanal = $data['agente_aduanal'];
            $expediente->agente_aduanal = $data['aduana'];
            $expediente->descripcion = $data['descripcion'];
            $expediente->status = $data['status'];
            return $expediente->save();
        }catch(\PDOException $ex){
            return false;
        }
    }

    /**
     * Consulta los pagos realizados a todas las facturas relacionadas al Expediente
     * @param  $id =id del expediente
     * @return array
     */
    public function pagosFacturasExp($id){

        return Movimiento::where('idExpediente', $id)
        ->leftJoin('facturas_cargadas', 'facturas_cargadas.id', 'movimientos.id_facturacargada')
        ->select ('facturas_cargadas.*','movimientos.polizaContable','movimientos.monto_anterior','movimientos.uidPago','movimientos.fechaPago','movimientos.monto_factura')
        ->where('idTipo', 1)->get();

    }

    /**
     * Consulta los pagos de las facturas y realiza las operaciones para calcular el SALDO final del estado de cuenta
     * @param  $id 
     * @return array()
     */
    public function pagosFacturasEstCta($id){

        $facturasCargadas = DB::table('facturas_cargadas')
            ->leftJoin('movimientos','movimientos.id_facturacargada','=','facturas_cargadas.id')
            ->select('facturas_cargadas.tipo_factura','json_cfdi','total as total_factura','monto_pagado','facturas_cargadas.id','movimientos.id as id_mov','facturas_cargadas.status_factura',
                DB::raw('case 
                    when total - monto_pagado is null then "0"
                    else total - monto_pagado

                    end as saldo'))
            ->where('id_expediente', $id)
            ->get();
        return $facturasCargadas;
    }
}
