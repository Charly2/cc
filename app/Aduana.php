<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aduana extends Model
{
    protected $table = 'catalogoaduanas';

    protected $fillable = [];
    protected $hidden = ['id'];

    public static function nombreAduana($aid)
    {
        $aduana = self::where('compuesto', $aid)->first();
        if ($aduana->count() > 0) {
            return $aduana->denominacion;
        }

        return 'No definido';
    }
}
