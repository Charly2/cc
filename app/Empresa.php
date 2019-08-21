<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $fillable = [];
    protected $hidden = ['updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pedimentos()
    {
        return $this->hasMany(Pedimento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expedientes()
    {
        return $this->hasMany(Expediente::class);
    }

    static function getEmpresaByRFC($rfc){
        $empresa = Empresa::where('rfc',$rfc)->get()->first();;
        return $empresa;

    }
}
