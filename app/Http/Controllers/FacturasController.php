<?php

namespace App\Http\Controllers;

use App\ConfigEmpresa;
use App\Expediente;
use App\FacturasCargadas;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use Unirest;
use PDF;
use Unirest\Request\Body;

class FacturasController extends Controller
{
    /*
     * @var nombre de le empresa
     * */
    protected $fileName;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
         $facturas = FacturasCargadas::where('id_expediente', $id)->get();
         return view('facturas.index',['expediente_id'=>$id,'facturas'=>$facturas]);
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

    public function formFactura ($id)
    {
        return view('facturas.crear_factura',['expediente_id'=>$id]);
    }

    public function subirFacturaPagos ($id, $tipo_factura)
    {
        return view('facturas.cargarfactura',['expediente_id'=>$id, 'tipo_factura' => $tipo_factura]);
    }

    public function show_facturaCargada($id, $expediente_id)
    {
        $factura_view=FacturasCargadas::where('id', $id)->get();
        foreach ($factura_view as  $row) {
            $factura = json_decode($row->json_cfdi,true);
            $id_control=$row->id_control;
        }

        return view('facturas.vista_factura',[
            'expediente_id'=>$expediente_id,
            'factura' => $factura ,
            'id_control'=>$id_control
        ]);
    }

     public function Show_PDF_Factura($id,$expediente_id)
    {
        $factura_view=FacturasCargadas::where('id', $id)->get();
        foreach ($factura_view as  $row) {
             $cfdi = json_decode($row->json_cfdi , true);
             $id_control=$row->id_control;
        }
        //$pdf = PDF::loadView('dompdf.wrapper');

        $pdf = PDF::loadView('facturas.factura_pdf',[
            'expediente_id'=>$expediente_id,
            'cfdi' => $cfdi ,
            'id_control'=>$id_control
        ]);

        //$pdf=PDF::loadHTML('<h1>Test</h1>');
        //return $pdf->download('ejemplo.pdf');
        return $pdf->stream();
    }

    /**
     * @param Request $request, id empresa
     * Cargar la factura XML y/o PDF
     */
    public function uploadFiles(Request $request,$id_empresa){

        $this->validate($request,[
                'factura_xml' => 'required|mimetypes:application/xml',
                'factura_pdf' => 'required|mimetypes:application/pdf'
            ],[
                'facturas_xml.required' => 'El archivo XML es requerido',
                'facturas_pdf.required' => 'El archivo PDF es requerido',
                'factura_xml.mimetypes' => 'El archivo no es un XML',
                'factura_pdf.mimetypes' => 'El archivo no es un PDF'
            ]);
        // las variables request que se usaran
        $inputs        =['factura_xml','factura_pdf'];
        $tipo_factura  = $request->input('tipo_factura');
        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
            ->where('configuracion','folder_storage')
            ->first();
        $expediente_id = $request->input('expediente_id');
        $ruta          = storage_path("app/$folderEmpresa->value/$expediente_id/facturas_xml/");
        $path          = "$folderEmpresa->value/$expediente_id/facturas_xml/";

        // recorremos cada input para buscar las variables
        foreach($inputs as $input){
            $file     = $request->file($input);
            $fileName = $file->getClientOriginalName();
            $mime = $file->getMimeType();
            // validamos el tipo de archivo que se carga
            if(!strcmp($mime,'application/xml') || !strcmp($mime,'text/xml')){
                $this->fileName = $fileName;

                Storage::put($path.$fileName,file_get_contents($file->getRealPath()));
                $this->save_xml($ruta.$fileName, $expediente_id, $tipo_factura, $fileName, $path.$fileName);
            }elseif(!strcmp($mime,'application/pdf') || !strcmp($mime,'text/pdf')){
                // se genera el archivo pdf con el mismo nombre que el archivo xml
                $archivo = explode('.',$this->fileName);
                Storage::put($path.$archivo[0].'.pdf',file_get_contents($file->getRealPath()));
            }

        }
        $facturas = FacturasCargadas::where('id_expediente', $expediente_id)->get();
        return view('facturas.index',['expediente_id'=>$expediente_id,'facturas'=>$facturas]);
    }


    public function save_xml($upload, $expediente_id, $tipo_factura, $fileName, $ruta)
    {
        $file = $upload;
        $xml_contenido = file_get_contents($file);

        $xml = simplexml_load_file($file);


        $ns  = $xml->getNamespaces(true);

        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);
         
        $array = array();

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
            //dd($row['@attributes']);
            $conceptos[]= array(
                'cantidad'      => $row['@attributes']['Cantidad'], // *
                //'unidad'        => $row['@attributes']['Unidad'], // *
                'descripcion'   => $row['@attributes']['Descripcion'], // *
                'valorUnitario' => $row['@attributes']['ValorUnitario'], // *
                'importe'       => $row['@attributes']['Importe'] // *
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
            $infoArray['@attributes']['UUID'] = strtoupper($infoArray['@attributes']['UUID']);
            $infoArray    = ($infoArray['@attributes']);
            $array['tfd'] = $infoArray ;
        } 
        
        $json_cfdi = json_encode($array);
        $data      = json_decode($json_cfdi, true);
        $respuesta    = $this->registrarFacturaWS($data);

        $codeResponse = $respuesta->code;
        $data_resp         = $respuesta->body;
        //$data_resp    = json_decode($resp);

        //dd($data_resp->{"Numero de factura"});

        if ($codeResponse==200 && $data_resp->result === true) {
            $interno = $data_resp->interno;
            $poliza  = $data_resp->{"Numero de factura"};
            $tipo    = 1;
        } elseif($codeResponse===200 && $data_resp->result === 11)  {
            $interno = $data_resp->interno;
            $poliza  = $data_resp->result;
            $tipo    = $data_resp->result;
            //echo  'La factura '.$fileName.' '.$data_resp->mensaje;
            //exit();
        }elseif($codeResponse===200 && $data_resp->result === 10 || $data_resp->result === 9 )  {
            $interno = '';
            $poliza  = '';
            $tipo    = $data_resp->result;
            echo  'Error '.$data_resp->mensaje.'- '.$fileName;
        }else{
            $interno = '';
            $poliza  = '0';
            $tipo    = '0';
            echo  'Error '.$data_resp->mensaje.'- '.$fileName;   
        }

        //dd([$interno,$poliza,$tipo]);

        $FacturasCargadas = new FacturasCargadas;
        $expediente       = Expediente::findOrFail($expediente_id);

        $FacturasCargadas->id_agente       = $expediente->agente_aduanal;
        $FacturasCargadas->id_usuario      = session()->get('id_usuario');
        $FacturasCargadas->id_expediente   = $expediente_id;
        $FacturasCargadas->formaDePago     = $data['cfdiComprobante']['FormaPago']; // *
        $FacturasCargadas->tipo_factura    = $tipo_factura;
        $FacturasCargadas->poliza          = $interno.'-'.$poliza;
        $FacturasCargadas->emisor_rfc      = $data['Emisor']['Rfc']; // *
        $FacturasCargadas->emisor_nombre   = $data['Emisor']['Nombre']; // *
        $FacturasCargadas->receptor_rfc    = $data['Receptor']['Rfc']; // *
        if(array_key_exists('Nombre', $data['Receptor'])){
            $FacturasCargadas->receptor_nombre = $data['Receptor']['Nombre']; // *
        }
        $FacturasCargadas->total           = $data['cfdiComprobante']['Total']; // *
        $FacturasCargadas->fecha           = $data['cfdiComprobante']['Fecha']; // *
        
        $FacturasCargadas->xml_file        =  $ruta;

        $FacturasCargadas->json_cfdi       = $json_cfdi;

        $FacturasCargadas->folio = $interno;


        if ($FacturasCargadas->save()) {
            echo 'La factura '. $fileName .' se inserto correctamente';
        } else {
            echo 'ups hubo una falla al cargar la factura '.$fileName;
        }
    }

    /**
     * @param $file_name
     * @param string $formato
     *
     * @return $this
     */
    public function download($id){
        $factura = FacturasCargadas::where('id', $id)->firstOrFail();
        $formato = 'xml';
        $pathFile = $factura['xml_file'];
        $file_name = $factura['xml_file'];
        $file = $file_name.'.'.$formato;

        if(!Storage::exists($pathFile)){
            abort(404);
        }

        $content = Storage::get($pathFile);
        $mime = Storage::mimeType($pathFile);

        return (new Response($content, 200))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'attachment; filename='.$file);
    }

    /**
     * @param $data
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|null
     */
    public function registrarFacturaWS($data)
    {

        $fecha = date("Y-m-d");
        $headers = array('Accept' => 'application/json');
        $url = "https://www.cpavision.mx/cpareview/cpa/cx/exportacion_cap2/ws_factura_extranjera.php";
        $myBody = [
            'anio_s'        => date("Y"),
            'fecha1'        => $fecha,
            'fecha2'        => $fecha,
            'fecha3'        => $fecha,
            'idfiscal'      => $data["cfdiComprobante"]["Folio"], // *
            'mes_s'         => date("m"),
            'moneda1'       => '0',
            'montofactura'  => $data['cfdiComprobante']['Total'], // *
            'montopesos'    => $data['cfdiComprobante']['Total'], // *
            'nombre'        => $data['Emisor']['Nombre'], // *
            'numfactura'    => $data["cfdiComprobante"]["Folio"], // *
            'rfc_empresa'   => $data['Receptor']['Rfc'], // *
            'rfc_proveedor' => $data['Emisor']['Rfc'] // *
        ];


        $body = Body::form($myBody);

        $response = \Unirest\Request::post($url, $headers, $body);

       // $response = Request::post($url, $headers, $body);


        return $response;
    }

    public function facturaPDF($factura_id){
        $factura = FacturasCargadas::where('id', $factura_id)->first ();
        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
            ->where('configuracion','folder_storage')
            ->first();
        $empresa = $folderEmpresa->value;
        $factura_ruta = explode('/',$factura->xml_file);
        $factura_nombre = explode('.',$factura_ruta[count($factura_ruta)-1]);
        $path = $empresa.'/'.$factura->id_expediente.'/facturas_xml/'.$factura_nombre[0].'.pdf';

        $content = Storage::get($path);
        $mime = Storage::mimeType($path);

        return (new Response($content, 200))
            ->header('Content-Type', $mime)
            ->header('Expires','0')
            ->header('Cache-Control','must-revalidate')
            ->header('Pragma','public');
    }
}
