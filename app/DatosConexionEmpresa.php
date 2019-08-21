<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosConexionEmpresa extends Model
{
    //
    protected $fillable =['id_empresa','host','user','password','path'];
}
