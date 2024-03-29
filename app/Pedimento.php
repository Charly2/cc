<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pedimento extends Model
{
    /**
     * @var string
     */
    protected $table = 'pedimentos';

    /**
     * @var array
     */
    protected $fillable = [
        'pedimento',
        'aduanaDespacho',
        'fechaPago',
        'fechaPedimento',
        'impExpNombre',
        'tipoOperacion',
        'empresa_id',
        'json',
        'archivoM',
        'archivoPDF'
    ];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function aduana()
    {
        return $this->hasOne('App\Aduana', 'compuesto', 'aduanaDespacho');
    }

    public function getAduana(){
        return Agencia::where('id',470)->get()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }


    public function pedimentosAsignado()
    {
        return $this->belongsToMany('App\PedimentosAsignado');
    }
}
