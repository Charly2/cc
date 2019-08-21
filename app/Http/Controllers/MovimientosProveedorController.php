<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\FacturasCargadas;
class MovimientosProveedorController extends Controller
{
    public function save_xml()
    {
    	$id_control='B00092626';
    	$id_expediente='3';
    	$file =url("xml/B00092626/17473291AI1722559000000000001504896.xml");
		$xml_contenido=file_get_contents($file);

		$xml = simplexml_load_file($file); 
		$ns  = $xml->getNamespaces(true);
		$xml->registerXPathNamespace('c', $ns['cfdi']);
		$xml->registerXPathNamespace('t', $ns['tfd']);
		 
		$array=array();

		//empiezo a leer la informacion del CFDI
		foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
			$infoArray                = (array) $cfdiComprobante ;
			$infoArray                = ($infoArray['@attributes']);
			$array['cfdiComprobante'] =  $infoArray;
		} 

		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
			$infoArray       = (array) $Emisor ;
			$infoArray       = ($infoArray['@attributes']);
			$array['Emisor'] = $infoArray ;

		} 
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){ 
			$infoArray                = (array) $DomicilioFiscal ;
			$infoArray                = ($infoArray['@attributes']);
			$array['DomicilioFiscal'] = $infoArray ;
		} 
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn){ 
			$infoArray           = (array) $ExpedidoEn ;
			$infoArray           = ($infoArray['@attributes']);
			$array['ExpedidoEn'] = $infoArray ;
		} 
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
			$infoArray         = (array) $Receptor ;
			$infoArray         = ($infoArray['@attributes']);
			$array['Receptor'] = $infoArray ;
		} 
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){ 
			$infoArray                  = (array) $ReceptorDomicilio ;
			$infoArray                  = ($infoArray['@attributes']);
			$array['ReceptorDomicilio'] = $infoArray ;
		} 
		//conteo de conceptos de pago 
		$conteo_concepto=count(($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto')));
		$data_concepto=$xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto');
		$infoArray = json_decode(json_encode((array)$data_concepto), TRUE);	
		//for ($i=0; $i < $conteo_concepto; $i++) { 

			foreach ($infoArray as $row ) {
				$conceptos[]= array(
				'cantidad'      =>$row['@attributes']['cantidad'],
				'unidad'        =>$row['@attributes']['unidad'],
				'descripcion'   =>$row['@attributes']['descripcion'],
				'valorUnitario' =>$row['@attributes']['valorUnitario'],
				'importe'       =>$row['@attributes']['importe']

				);

			}
		//} 	
		$array['Conceptos']  = $conceptos ;
		
		foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 
			$infoArray         = (array) $Traslado ;
			$infoArray         = ($infoArray['@attributes']);
			$array['Traslado'] = $infoArray ;
		} 
		 

		foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
			$infoArray    = (array) $tfd ;
			$infoArray    = ($infoArray['@attributes']);
			$array['tfd'] = $infoArray ;
		} 
		
		$json_cfdi=json_encode($array);

		$data=json_decode($json_cfdi, true);
		


		$FacturasCargadas=new FacturasCargadas;
		
		$FacturasCargadas->id_agente       = session()->get('id_agencia');
		$FacturasCargadas->id_usuario      = session()->get('id_usuario');
		$FacturasCargadas->id_control      = $id_control;
		$FacturasCargadas->id_expediente      = $id_expediente;
		$FacturasCargadas->formaDePago     = $data['cfdiComprobante']['formaDePago'];
		$FacturasCargadas->emisor_rfc      = $data['Emisor']['rfc'];
		$FacturasCargadas->emisor_nombre   = $data['Emisor']['nombre'];
		$FacturasCargadas->receptor_rfc    = $data['Receptor']['rfc'];
		$FacturasCargadas->receptor_nombre = $data['Receptor']['nombre'];
		$FacturasCargadas->total           = $data['cfdiComprobante']['total'];
		$FacturasCargadas->fecha           = $data['cfdiComprobante']['fecha'];
		$FacturasCargadas->folio           = $data['cfdiComprobante']['serie'].' '.$data['cfdiComprobante']['folio'];
		$FacturasCargadas->xml_cfdi        = $xml_contenido;
		$FacturasCargadas->json_cfdi       = $json_cfdi;
		

		if ($FacturasCargadas->save()) {
			
			echo 'se inserto correctamente';
		} else {
			echo 'ups hubo una falla';
		}
		

		

		
    }
}
