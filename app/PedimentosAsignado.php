<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedimentosAsignado extends Model
{
    protected $table = 'pedimentos_asignados';
    protected $fillable = ['id_expediente', 'id_pedimento'];
}
