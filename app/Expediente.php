<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{

    protected $fillable = ['expediente', 'agente_aduanal', 'nombre', 'descripcion', 'empresa_id', 'status'] ;


    /**
     * @var array
     */
    protected $hidden = ['empresa_id', 'aduana_id', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aduana()
    {
        return $this->hasOne(Aduana::class,'compuesto','aduana_id');
    }


    public function agente()
    {
        $consulta = $this->belongsTo(Agencia::class,'id','agente_aduanal');

        return $consulta;
    }


    public function agente_expediente($id)
    {
        $consulta = $this->belongsTo(Agencia::class,'id','agente_aduanal');

        return $consulta;
    }


    public function agente_aduana(){

        $consulta = $this->belongsToMany(Aduana::class,'id','agente_aduanal');
        return $consulta;

    }

    public function documentos(){
        return $this->hasMany(Documento::class);
    }
}
