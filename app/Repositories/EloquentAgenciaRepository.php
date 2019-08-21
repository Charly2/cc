<?php

namespace App\Repositories;

use App\Agencia;

class EloquentAgenciaRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return Agencia::All();
    }

    // devolver todas las agencias según la configuración del usuario
	public function agenciasByPermission()
    {
        // obtiene el codigo de agente
        $id_permiso = auth()->user()->permiso_id;
        // solicitar los datos del agente
        $agencias = Agencia::where('id', $id_permiso)->get();
        if(empty($agencias)){
            $agencias = array();
        }

        return $agencias;
    }

    // devolver las agencias según los id de agencia enviados
    public function agenciasByIds($ids){
        $agencias = array();
        $agencias = Agencia::whereIn('id',$ids)->get();
        return $agencias;
    }
}
