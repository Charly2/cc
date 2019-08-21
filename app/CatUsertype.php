<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatUsertype extends Model
{
    protected $table ='cat_usertype';

    public static function usertypePermission()
    {
        $usertype_id = auth()->user()->usertype_id;
        $id_permiso  = auth()->user()->permiso_id;
        $id          = auth()->user()->id;

        //si es administrador permitir el acceso a todas las empresas
        if ($usertype_id=='1') {
            $usertypes = CatUsertype::all();

            //en caso que el tipo de usuario sea agente Aduanal o E´mpresa
        } elseif ($usertype_id=='2' || $usertype_id=='3' ) {
            $usertypes = CatUsertype::where('id',$usertype_id)->get();

        } else{
            $usertypes = array();
        }

        return $usertypes;

    }

}