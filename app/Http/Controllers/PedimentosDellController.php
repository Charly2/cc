<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PedimentosDellController extends Controller
{
      public function uploadFiles(Request $request){



        $file = Input::file('files')->getRealPath();

		$file          = $request->file('files');
		$fileName      = $file->getClientOriginalName();        

        \Excel::setDelimiter('|')->load($file, function($reader) {
                echo "<pre>";
                $reader->setDelimiter('|');
                print_r($reader->get());
        });

      	echo "recibido";
      	/*
        $tipo_factura  = $request->input('tipo_factura');
        $file          = $request->file('files');
        $fileName      = $file->getClientOriginalName();
        $path          = "uploads/pedimentos_dell/";
        $expediente_id = $request->input('expediente_id');
        

        if ($file->move($path, $fileName)) {
            echo "se movio correctamente el archivo";
            $upload=$path.$fileName;
            $this->save_xml($upload,$expediente_id,$tipo_factura);
        } else {
             echo "hubo un error";
        }
        */

       
    }

    public function subirPedimDell ()
    {
        return view('pedimento_dell.cargarpedimento');
    }


}
