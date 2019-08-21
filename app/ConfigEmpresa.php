<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigEmpresa extends Model
{
    protected $table = 'config_empresas';
    protected $fillable = ['empresa_id','configuracion','value'];
}
