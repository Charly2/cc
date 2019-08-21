<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrGenis\Library\XmlToArray;
use App\Http\Requests;

class JobUploadCoveController extends Controller
{
    public function uploadCove(){
    	$file =url("COVEternium.xml");
		$xml_contenido = file_get_contents($file);
		$result        = XmlToArray::convert($xml_contenido);
		$result        = collect($result)->tojson();

		//->only(['patenteAduanal']);
       //return $result->keyBy('tipoOperacion');

		//$result = array_only($result, ['eDocument','tipoOperacion','patenteAduanal']);
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		echo "<h1><-----------------------------------></h1>";
		$file =url("COVEcisco.xml");
		$xml_contenido=file_get_contents($file);
		$result=XmlToArray::convert($xml_contenido);
		//$result=array_get($result, 'e-document');
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		echo "<h1><-----------------------------------></h1>";

    }
}
