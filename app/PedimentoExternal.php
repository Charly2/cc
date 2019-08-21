<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedimentoExternal extends Model
{
    protected $table = 'pedimentos_external';
    public $timestamps = false;
    protected $fillable = [];
    protected $hidden = [];
}
