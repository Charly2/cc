<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $table = 'agencias';
    protected $fillable = ['nombre','rfc'];

    public function expedientes(){
        return $this->hasMany('App\Expediente');
    }

}
