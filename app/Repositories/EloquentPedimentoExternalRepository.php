<?php

namespace App\Repositories;

use DB;

class EloquentPedimentoExternalRepository
{
    /**
     * @param array $conditions
     * @param bool  $paginate
     *
     * @return mixed
     */
    public function facReviewInCt(array $conditions, $paginate = true)
    {
        $pedimentos = DB::table('pedimentos_external')
        ->join('pedimentos', 'pedimentos_external.pedimento_ct', '=', 'pedimentos.pedimento')
        ->where('pedimentos.empresa_id', '=', $conditions['id'])
        ->where('pedimentos_external.rfc_receptor', '=', $conditions['rfc'])
        ->orWhere('pedimentos_external.rfc_emisor', '=', $conditions['rfc']);

        return $paginate ? $pedimentos->paginate(20) : $pedimentos->get();
    }

    /**
     * @param array $conditions
     * @param bool  $paginate
     *
     * @return mixed
     */
    public function facReviewNotInCt(array $conditions, $paginate = true)
    {
        $pedimentos = DB::table('pedimentos_external')
        ->select('pedimento_ct as pedimento', 'nombre_emisor', 'rfc_emisor', 'nombre_receptor', 'rfc_receptor', 'uuid')
        ->where('pedimentos_external.rfc_receptor', '=', $conditions['rfc'])
        ->orWhere('pedimentos_external.rfc_emisor', '=', $conditions['rfc'])
        ->whereNotIn('pedimento_ct', function ($query) use ($conditions) {
            $query->select(DB::raw('pedimento'))
                  ->from('pedimentos')
                  ->where('empresa_id', '=', $conditions['id']);
        });

        return $paginate ? $pedimentos->paginate(20) : $pedimentos->get();
    }

    /**
     * @param array $conditions
     *
     * @return mixed
     */
    public function totalPedimentosFr(array $conditions)
    {
        return DB::table('pedimentos_external')
        ->select(DB::raw('COUNT(*) as contador'))
        ->where('pedimentos_external.rfc_receptor', '=', $conditions['rfc'])
        ->orWhere('pedimentos_external.rfc_emisor', '=', $conditions['rfc'])
        ->get();
    }

    /**
     * @param array $conditions
     *
     * @return mixed
     */
    public function totalPedimentosEncontrados(array $conditions)
    {
        return DB::table('pedimentos_external')
        ->select(DB::raw('COUNT(*) as contador'))
        ->join('pedimentos', 'pedimentos_external.pedimento_ct', '=', 'pedimentos.pedimento')
        ->where('pedimentos.empresa_id', '=', $conditions['id'])
        ->where('pedimentos_external.rfc_receptor', '=', $conditions['rfc'])
        ->orWhere('pedimentos_external.rfc_emisor', '=', $conditions['rfc'])
        ->get();
    }

    /**
     * @param array $conditions
     *
     * @return mixed
     */
    public function totalPedimentosNoEncontrados(array $conditions)
    {
        return DB::table('pedimentos_external')
        ->select(DB::raw('COUNT(*) as contador'))
        ->where('pedimentos_external.rfc_receptor', '=', $conditions['rfc'])
        ->orWhere('pedimentos_external.rfc_emisor', '=', $conditions['rfc'])
        ->whereNotIn('pedimento_ct', function ($query) use ($conditions) {
            $query->select(DB::raw('pedimento'))
                  ->from('pedimentos')
                  ->where('empresa_id', '=', $conditions['id']);
        })->get();
    }
}
