<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incoterms extends Model
{
    protected $table = 'catalogoIncoterms';
    public $timestamps = false;
    protected $fillable = [];
    protected $hidden = ['id'];

    public static function nombreIncoterms($id)
    {
        return self::where('identificador', $id)
            ->first()
            ->descripcion;
    }
}
