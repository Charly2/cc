<?php

namespace App\Repositories;

use App\Expediente;
use App\Pedimento;
use DB;
use Session;

/**
 * Class EloquentPedimentoRepository.
 */
class EloquentPedimentoRepository
{
    /**
     * @param $empresaId
     * @param $anio
     * @param $mes
     *
     * @return mixed
     */

    public function pedimentosPeriodo($empresaId, $anio, $mes)
    {
        $pedis =  Pedimento::with('aduana')
        ->where('empresa_id', $empresaId)
        ->whereYear('created_at', $anio)
        ->whereMonth('created_at', $mes)
        ->orderBy('created_at', 'desc');
        dd(Pedimento::with('aduana'));
        return $pedis;
    }

    // funcion para actualizar en tabla pedimentos el expediente de pedimentos
    public function asignarPedimento($id_pedimento,$id_expediente,$id_aduana){
        //actualizo el pedimento poblando el id_expediente
        $updatePedimento = Pedimento::where('id', $id_pedimento)->update(['expediente_id' => $id_expediente]);

        //actualizo el expediente poblando el id de la Aduana proveniente del pedimento
        $updateExpediente = Expediente::where('id' ,$id_expediente)->update(['aduana_id' => $id_aduana] );

        return $updatePedimento;
    }

    // funcion que devuelve pedimento y empresa
    public function getPedimentoEmpresa($id_pedimento)
    {
        $pedimento  = Pedimento::where('pedimentos.id',$id_pedimento)->leftJoin('empresas','pedimentos.empresa_id','=','empresas.id')->first();

        return $pedimento;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function updateOrCreate(array $data)
    {
        return Pedimento::updateOrCreate([
            'pedimento' => $data['pedimento'],
            'empresa_id' => $data['empresa_id'],
        ], $data);
    }

    /**
     * @param $pedimento
     *
     * @return mixed
     */
    public function findByPedimento($pedimento)
    {
        return Pedimento::where('pedimento', '=', $pedimento)
        ->firstOrFail();
    }



    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id)
    { 
        return Pedimento::where('id', $id)
        ->firstOrFail();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function PedimReturnJson($id)
    { 
        $pedimento = Pedimento::where('id', $id)
        ->firstOrFail();
        return json_decode($pedimento->json , true);
    }

    /**
     * @param $idPedimento
     * @param $idExpediente
     *
     * @return mixed
     */
    
    public function findByIdExpediente($idPedimento,$idExpediente){
         return Pedimento::where('id',$idPedimento)
                ->where('expediente_id',$idExpediente)
                ->firstOrFail();
    }


    public function findByIdEmpresa(){
        $idEmpresa  = Session::get('id');
        return Pedimento::where('empresa_id', $idEmpresa)->get();
    }


    /**
     * @param $id = idpedimento
     *
     * @return mixed
     */
    public function getPedimentosAsignados($id){

        $pedimentos_asignados = DB::table('pedimentos')
            ->leftJoin('pedimentos_asignados', 'pedimentos.id', 'pedimentos_asignados.id_pedimento')
            ->where('pedimentos.expediente_id', '=', $id)
            ->select('pedimentos.*')
            ->get();

        return $pedimentos_asignados;
    }


}
