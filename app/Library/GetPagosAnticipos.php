<?php
namespace App\Library;
use Illuminate\Http\Request;
use App\Http\Requests;
class GetPagosAnticipos
{

    public function saldoDeudor()
    {

    }

    /**
     * [saldoTotal description]
     * @param  [int] $facturaTotal   [description]
     * @param  [array] $pagosAnticipos [Recibo tanto pagos como anticipos]
     * @return [type]                 [description]
     */
    public function saldoTotal($facturaTotal,$pagosAnticipos)
    {
        
        //sumo el monto pagado
        $totalMovimientos = $pagosAnticipos->sum('monto_pagado');
        //hago la resta para saber cuanto falta por pagar
        $total = $facturaTotal - $totalMovimientos;
        echo count($pagosAnticipos) ? "si hay pagos y el saldo es ".$total :  "no hay pagos y el saldo es ".$total;
        
    }
    
    public function saldoFavor()
    {

    }
    

}
