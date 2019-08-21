<?php

namespace App\Repositories;

use App\Cove;
use DB;

class EloquentCoveRepository
{
    /**
     * 
     * @return mixed
     */
    public function getAll()
    {
        return Cove::all();
    }


    public function getCovePedimentoByidExp($id){
    	$coves  = Cove::where('id_expediente', $id)
            ->leftJoin('pedimentos', 'pedimentos.expediente_id', '=', 'coves.id_expediente')
            ->leftJoin('expedientes','expedientes.id','=','coves.id_expediente')
            ->where('expedientes.id',$id)
            ->select('coves.*', 'pedimentos.json')->get();

        return $coves;
    }
}
