<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Http\Requests\SaveAgenciaRequest;



class AgenteAduanalController extends Controller
{
    //

    public function create(){
        return view('agentes.create');
    }
    public function store(SaveAgenciaRequest $request){
        $re = $request->save();

        return redirect('/agenteaduanal');

    }


    public function edit($id){
        $agencia = Agencia::find($id);

        return view('agentes.edit',['agencia'=>$agencia]);

    }

    public function update(){

        $data = request()->validate([
            'id' => "required",
            "nombre_agencia" => ["required"],

        ],[
            'nombre_agencia.required' =>"El compo nombre es obligatorio",
        ]);



        $agencia = Agencia::find($data['id']);
        $agencia->nombre = $data['nombre_agencia'];

        $agencia->save();

        return redirect('/agenteaduanal');


    }


}
