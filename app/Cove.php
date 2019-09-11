<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cove extends Model
{
    //
    protected $attributes = [
        'pdfs' => ''
    ];

    public function getTotalMercancias(){
        $mercancias = (array) json_decode($this->json_cove)->comprobantes->mercancias;

        if (array_key_exists('descripcionGenerica',$mercancias) ){
            return number_format($mercancias['valorTotal'],2,'.',' ');
        }else{
            $total = 0;
            foreach($mercancias as $me){
                $total += $me->valorTotal;
            }
            return number_format($total,2,'.',' ');
        }


    }

    public function getFechaExp(){

        return json_decode($this->json_cove)->comprobantes->fechaExpedicion;
    }
    public function getEmisor(){

        return json_decode($this->json_cove)->comprobantes->emisor->nombre;
    }
}
