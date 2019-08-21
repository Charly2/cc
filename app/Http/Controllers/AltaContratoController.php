<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\AvisoAltaContrato;
use Validator;
class AltaContratoController extends Controller
{
        public function __construct(){

      
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => 'hola']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'json_result' => 'required'
        ]);

        if ($validatedData->passes()) {
            $respuesta = AvisoAltaContrato::create($request->all());
            if ($respuesta) {
                return response()->json(['sucess'=> 'Se guardo correctamente la informacion', 'data' =>$respuesta, 'code' => 201 ], 201);
            } else {
                return response()->json(['error' => 'ocurrio un error al guardar los datos de la petición', 'code' => 400], 400);
            }
            
        }


        return response()->json(['error'=>$validatedData->errors()->all()]);




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
