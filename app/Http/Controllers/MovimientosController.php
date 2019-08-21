<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\CatalogoTipoPago;
use App\Expediente;
use App\FacturasCargadas;
use App\Movimiento;
use App\Repositories\CatalogoTipoPago\ICatalogoTipoPagoRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Session;
use Unirest\Request\Body;
use View;

class MovimientosController extends Controller
{
    public function getFormPago(int $id)
    {
        //$proveedores=Proveedore::where('id_agencia', $id)->get();

        $facturas = FacturasCargadas::where('id_expediente', $id)
                //en caso que sea null
                ->whereNull('status_factura')
                ->get();

        $nombre_agencia=session()->get('nombre_agencia');
        $expediente = Expediente::findOrFail($id);
        $tipoPago = CatalogoTipoPago::all();
        $agencias = Agencia::all();
        return View::make('movimiento.registrar_pago', [
            'expediente' => $expediente, 'tipoPago' => $tipoPago, 'id' => $id, 'facturas' => $facturas ,'nombre_agencia' =>$nombre_agencia
        ]);
    }

    public function getFormAnticipo(int $id)
    {
        //$nombre_agencia=session()->get('nombre_agencia');        
        $expediente = Expediente::findOrFail($id);
        $tipoPago = CatalogoTipoPago::all();
        $agencia = Agencia::findORFail($expediente['agente_aduanal']);
        return View::make('movimiento.registrar_anticipo', [
            'expediente' => $expediente, 'tipoPago' => $tipoPago, 'id' => $id, 'agencia' => $agencia
        ]);
    }

    public function store(Request $request)
    {

        switch ($request->forma_pago) {
            case '1':
                $formaDePago = "EFECTIVO";
                break;
            case '2':
                $formaDePago = "CHEQUE";
                break;
            case '3':
                $formaDePago = "TRANSFERENCIA";
                break;
            default:
                $formaDePago = "OTRO";
                break;
        }



        $mov = new Movimiento;

        $respuesta="";$resp="";$codeResponse="";
        if ($request->tipoPago == 2) {
            $mov->tipo = 'Anticipo';
            $mov->idTipo = 2;
        } else {
            $mov->tipo = 'Pago';
            $mov->idTipo = 1;
            $respuesta = $this->registrarPagoWS($request);
            $resp         = $respuesta->body;
            $codeResponse = $respuesta->code;
        }








        if ($codeResponse==200) {
            //$string = '{result: true, mensaje: null, interno: 1, poliza:1, tipo: PE}';
            $string = str_replace('{','{"',$resp);
            $string = str_replace('}','"}',$string);
            $string = str_replace(':','":"',$string);
            $string = str_replace(', ','","',$string);
            $datos   = json_decode($string);

            $interno = $datos->interno;
            $poliza  = $datos->poliza;
            $tipo    = $datos->tipo;
        } else {
            $interno = '';
            $poliza  = '';
            $tipo    = '';
        }





        $factura  = FacturasCargadas::where('id', $request->id_factura)->get();


        dd($factura);

       
        $cantidad = str_ireplace("$","", $request->monto); 
        $cantidad = str_ireplace(",","", $cantidad); 
        $mov->monto_factura     = $factura['0']['total']; 
        $mov->monto_pagado      = $cantidad; 
        $mov->monto_anterior    = $cantidad;
        $mov->polizaContable    = $tipo.' '.$poliza;
        $mov->rfc               = $request->rfc;
        $mov->idExpediente      = $request->id_expediente;
        $mov->fechaPago         = date("Y-m-d\TH:i:s", strtotime(date('Y-m-d H:i:s')));
        $mov->uidPago           = $formaDePago;
        $mov->id_agencia        = $factura['0']['id_agente'];
        $mov->id_empresa        = Session::get('id');
        $mov->id_facturacargada = $request->id_factura;


        if ($mov->save()) {
            $fcc  = FacturasCargadas::where('id', $request->id_factura)
            ->update(['status_factura'=> 'Pagado']);


        } 


        return redirect('/expedientes/'.$request->id_expediente);
       /*
        $respuesta = $this->registraPago();
        $datos = $respuesta->getBody();
        $resp=response()->json($datos);
        echo $resp;
        exit();
        */
    }

    /**
     * @param $id
     * @return mixed
     */
    public function download($id){
        /*$pago['xml'] = $id;*/
        /*$pago = Movimiento::where('id', $id)->firstOrFail();*/
        /*$pago = Movimiento::where('id', $id)->firstOrFail();*/
        $pago = FacturasCargadas::where('id', $id)->firstOrFail();
            /*->leftJoin('facturas_cargadas', 'facturas_cargadas.id', ' =', 'movimientos.id_facturacargada')
            ->select ('facturas_cargadas.*','movimientos.polizaContable','movimientos.monto_anterior','movimientos.uidPago','movimientos.fechaPago','movimientos.monto_factura')
            ->where('idTipo', 1)->get();*/
        $formato = 'xml';
        $pathFile = $pago['xml_file'];
        $file_name = $pago['xml_file'];
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

    public function registraPago()
    {
        $client = new \GuzzleHttp\Client();
        $body = array('doc' => 1, 'tipo' => 1.4, 'ejercicio' => 2017, 'periodo' => 1 , 'movimientos' => array('cuenta' => "1000-002-1122", 'carga' => "abono", 'monto' => 3500));
        $response = $client->post('http://54.165.25.115/ws/polizaFinancials.php', array('body' => json_encode($body)));  
        return $response;
    }


    public function registrarPagoWS($request)
    {

        $fecha = date("Y-m-d");
        $cantidad = str_ireplace("$","", $request->monto);
        $cantidad = str_ireplace(",","", $cantidad);


        $factura=FacturasCargadas::where('id', $request->id_factura)->get();


        $json_cfdi=json_decode($factura[0]['json_cfdi'],true);


        $headers = array('Accept' => 'application/json');
        $url = 'https://www.cpavision.mx/cpareview/cpa/cx/documentos_cap2/ws_pagos_anticipos.php';
        $myBody = [
            //PAGO PESOS
            'asiento'        =>'505',//:505                                DOL 506, MN 505
            'cambio'         =>'',
            'ctabanco'       =>'102-01-01',
            'documento'      =>'12',//:12
            'fecha2'         =>$fecha,//:2017-07-01
            'fechaV'         =>$fecha,//:2017-07-01
            'importe'        =>$cantidad ,//:1224
            'modulo'         =>'2',//:2                                1 CXC, 2 CXP
            'moneda'         =>'0',//:0
            'nombre'         =>$factura[0]['emisor_nombre'],
            'nomContrato'    =>$json_cfdi["cfdiComprobante"]["TipoDeComprobante"],
            'serie'          =>$json_cfdi["cfdiComprobante"]["Folio"],
            'tipo_documento' =>'7',//:7                        DOL 8,  MN 7
            'rfc_empresa'    =>$factura[0]['receptor_rfc'],//:DPM140627I40
            'rfc_proveedor'  =>$factura[0]['emisor_rfc']//:FHI8704277E9 emisor
        ];



        $body = Body::form($myBody);

        $response = \Unirest\Request::post($url, $headers, $body);

        return $response;

    }

    public function indexPago($id,$id_expediente){
        $factura_view=FacturasCargadas::where('id', $id)->get();
        foreach ($factura_view as  $row) {
             $factura = json_decode($row->json_cfdi,true);
             $id_control=$row->id_control;
        }

        return view('facturas.pagos',[
        'expediente_id'=>$id_expediente,
        'factura' => $factura ,
        'id_control'=>$id_control,
        'id' => $id]);
    }
}