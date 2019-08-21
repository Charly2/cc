<?php
/**
 * Created by PhpStorm.
 * User: cesar
 * Date: 27/04/16
 * Time: 08:36 AM.
 */
namespace App\Repositories;

use App\User as  Usuario;
use Illuminate\Support\Facades\Hash;
use DB;
class EloquentUsuarioRepository
{ 
    /**
     * @return Collection
     */
    public function all()
    {
       // return Usuario::all();
        $usuarios = DB::table('usuarios')
        ->leftjoin('cat_usertype', 'cat_usertype.id', '=', 'usuarios.usertype_id')
        ->select('usuarios.*', 'cat_usertype.usertype');
        return $usuarios->get();
    }

  public function userAgencia($id_permiso,$id)
    {
 
        $usuarioAgencia = DB::table('usuarios')
        ->leftjoin('agencias', 'agencias.id', '=', 'usuarios.permiso_id')
        ->select('usuarios.*', 'agencias.nombre','agencias.rfc')
        ->where('agencias.id', '=',$id_permiso)
        ->where('usuarios.id', '=',$id);
        return $usuarioAgencia->first();
      
    }


    /**
     * @return Usuario
     */
    public function find($id)
    {
        return Usuario::find($id);
    }

    public function findOrFail($id)
    {
        return Usuario::findOrFail($id);
    }

    public function create(array $data)
    {
        $usuario              = new Usuario();
        $usuario->username    = $data['username'];
        $usuario->email       = $data['email'];
        $usuario->password    = Hash::make($data['password']);
        $usuario->usertype_id = $data['usertype_id'];
        $usuario->activo      = 1;
        //modificar despues por catalogo permiso_id
        $usuario->permiso_id  = $data['permiso_id'];
  
        

        return $usuario->save();
    }

    public function update(array $data, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->username = $data['username'];
        $usuario->email = $data['email'];
        if (isset($data['password']) && !empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }
        $usuario->usertype_id = $data['usertype_id'];

        return $usuario->save();
    }

    public function delete($id)
    {
        return Usuario::findOrFail($id)->delete();
    }

    public function asignarEmpresa($usuarioId, $empresaId)
    {
        $usuario = Usuario::findOrFail($usuarioId);

        return $usuario->empresas()->sync([$empresaId], false);
    }

    public function desasignarEmpresa($usuarioId, $empresaId)
    {
        $usuario = Usuario::findOrFail($usuarioId);

        return $usuario->empresas()->detach($empresaId);
    }


     public function usuariosByPermission(){
        $usertype_id = auth()->user()->usertype_id;
        //si es administrador permitir el acceso a todas las empresas  
        if ($usertype_id=='1') {
                $usuarios = DB::table('usuarios')
                ->leftjoin('cat_usertype', 'cat_usertype.id', '=', 'usuarios.usertype_id')
                ->select('usuarios.*', 'cat_usertype.usertype')->get();
        //en caso que el tipo de usuario sea agente Aduanal o E´mpresa
             } elseif ($usertype_id=='2' || $usertype_id=='3' ) {
                $usuarios = DB::table('usuarios')
                ->leftjoin('cat_usertype', 'cat_usertype.id', '=', 'usuarios.usertype_id')
                ->select('usuarios.*', 'cat_usertype.usertype')
                ->where('usertype_id',$usertype_id)->get();
            
             } else{
                 $usuarios =array();
            }  

            return $usuarios;



    }


}
