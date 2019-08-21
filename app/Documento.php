<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable =['expediente_id','nombreDocumento'] ;
    protected $attributes = [
        'nota' => ''
    ];

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }


}
