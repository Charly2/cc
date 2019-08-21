<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\ConfigEmpresa;
use App\Cove;
use App\Expediente;
use App\FacturasCargadas;
use App\Http\Requests;
use App\Http\Requests\UpdateExpedienteRequest;
use App\Library\GetPagosAnticipos;
use App\Movimiento;
use App\MovimientoAgente;
use App\Pedimento;
use App\Repositories\EloquentAduanaRepository;
use App\Repositories\EloquentCoveRepository;
use App\Repositories\EloquentExpedienteRepository;
use App\Repositories\EloquentPedimentoRepository;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use View;

class ExpedienteController extends Controller
{
    /**
     * @var IExpedienteRepository
     */
    protected $expediente;

    /**
     * @var IAduanaRepository
     */
    protected $aduana;

    /**
     * @var IPedimentoRepository
     */
    protected $pedimento;

    /**
     * @var ICoveRepository
     */
    protected $cove;

    /**
     * ExpedienteController constructor.
     * @param IExpedienteRepository $expedienteRepository
     * @param IAduanaRepository $aduanaRepository
     */
    public function __construct(EloquentExpedienteRepository $expedienteRepository,EloquentAduanaRepository $aduanaRepository, EloquentPedimentoRepository $pedimentoRepository , EloquentCoveRepository $coveRepository)
    {
        $this->middleware('expediente_approved', ['only' => ['show', 'edit', 'update']]);
        $this->expediente = $expedienteRepository;
        $this->aduana     = $aduanaRepository;
        $this->pedimento  = $pedimentoRepository;
        $this->cove       = $coveRepository;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {


        $expedientes = $this->expediente->getExpedientesByEmpresaId(
            Session::get('id')
        )->get();

        return View::make('expediente.index',[
            'expedientes' => $expedientes
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $aduanas = $this->aduana->getAll();
        $agencias = Agencia::all();

        return View::make('expediente.create',[
            'aduanas' => $aduanas,'agencias'=>$agencias
        ]);
    }

    /**
     * @param Requests\StoreExpedienteRequest $request
     * @return RedirectResponse
     */
    public function store(Requests\StoreExpedienteRequest $request) : RedirectResponse
    {
        $data = $request->all();


        /*$data['expediente'] = NumExpedienteService::generate('',$this->expediente->getLastId());*/
        $data['expediente'] = str_pad($this->expediente->getLastId()+1, 6, 0, STR_PAD_LEFT);
        $data['empresa_id'] = Session::get('id');
        $data['status']     = 'Abierto';



        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))->where('configuracion','folder_storage')->first();


        $expediente    = Expediente::create($data);
        $id = $expediente['id'];
        $path          = storage_path()."/app/$folderEmpresa->value/$id/";
        File::makeDirectory($path, $mode = 0777, true, true);
        if(!File::exists($path)){
            // No se creo el folder correctamente
            echo 'No se creo la carpeta correctamente';
        }
        return redirect()->route('expedientes.index');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show( $id)
    {




        $documentos           = Expediente::findOrFail($id)->documentos()->get();

        $folderEmpresa        = ConfigEmpresa::where('empresa_id',session()->get('id'))
                                 ->where('configuracion','folder_storage')->first();
        $pedimentos_asignados = $this->pedimento->getPedimentosAsignados($id);

        $expediente           = $this->expediente->getExpedienteById($id);
        $coves                = $this->cove->getCovePedimentoByidExp($id);
        $pagos                = $this->expediente->pagosFacturasExp($id);
        $pagos_External       = FacturasCargadas::where('id_expediente', $id)->get();
        $anticipos            = Movimiento::where('idExpediente', $id)->where('idTipo', 2)->get();
        $pedimentos           = Pedimento::with(['aduana'])->where([['empresa_id',Session::get('id')],['expediente_id',$id]])->get();
       // dd($pedimentos);

        $cco = 0;
        $arregloCoves = [];
        foreach ($coves as $cove){
            $collection = collect(json_decode($cove->json_cove,true));
            $arregloCoves[$cco] = $this->array_change_key_case_recursive($collection['comprobantes']);
            // modificamos el formato de fecha
            $fecha = '';
            if(isset($arregloCoves[$cco]['fechaexpedicion'])){
                $fecha = Carbon::createFromFormat('Y-m-d',$arregloCoves[$cco]['fechaexpedicion'])->format('d/m/Y');
            }
            $arregloCoves[$cco]['fechaexpedicion'] = $fecha;
            $cco ++;
        }
        /*Storage::setVisibility('app/GRU9712028T1_luigi/29/facturas_xml/GRU9712028T1L01767542.xml', 'public');*/
        /*Storage::getVisibility(storage_path('app/GRU9712028T1_luigi/29/facturas_xml/GRU9712028T1L01767542.XML'));*/
        return View::make('expediente.expediente',['pedimentos_asignados'=> $pedimentos_asignados,'coves'=>$coves, 'arr_cove' => $arregloCoves,
            'expediente' => $expediente, 'pagos' => $pagos, 'pedimentos' => $pedimentos, 'anticipos' => $anticipos ,'pagos_External'=> $pagos_External,
            'documentos' => $documentos , 'folderEmpresa'=>$folderEmpresa
        ]);
        
    }

    public function array_change_key_case_recursive($input){
        $input = array_change_key_case($input, CASE_LOWER);
        foreach($input as $key=>$array){
            if(is_array($array)){
                $input[$key] = $this->array_change_key_case_recursive($array);
            }
        }
        return $input;
    }

    /**
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit( $id)
    {


        
        $expediente = $this->expediente->getExpedienteById($id);

        $agencias = Agencia::all();
        $agente[] = null;
        foreach($agencias as $agencia){
            if($agencia['id'] == $expediente['agente_aduanal']){
                $agente[] = $agencia['nombre'];
                $agente[] = $agencia['id'];
            }
        }

        return View::make('expediente.edit',[
            'expediente' => $expediente,
            'agencias'   => $agencias,
            'agente'     => $agente
        ]);
    }

    /**
     * @param UpdateExpedienteRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(UpdateExpedienteRequest $request, int $id) : RedirectResponse
    {
        //dd($request->all());
        $this->expediente->update($request->all(), $id);

        return redirect()
            ->back()
            ->with('message','Expediente actualizado.');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function registrarPago(int $id) : \Illuminate\View\View
    {
        $expediente = Expediente::findOrFail($id);

        return View::make('expediente.registrar_pago',[
            'expediente' => $expediente
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function registrarAnticipo(int $id) : \Illuminate\View\View
    {
        $expediente = Expediente::findOrFail($id);

        return View::make('expediente.registrar_anticipo',[
            'expediente' => $expediente
        ]);
    }

    /**
     * @return string
     */
    public function pedimentosUnsigned(int $id) : string
    {
        $pedimentos = Pedimento::with(['aduana'])
        ->where('empresa_id',Session::get('id'))
        ->whereNull('expediente_id')
        ->paginate(50);
        return View::make('pedimento.unsigned',[
            'pedimentos' => $pedimentos, 
            'id_expediente' => $id
        ])->render();
    }

    /**
     * @param Requests\StoreExpedienteRequest $request
     * @return RedirectResponse
     */
    public function registrarPagoMovimiento(Requests\StoreExpedienteRequest $request) : RedirectResponse
    {
        $data = $request->all();
        
        $this->expediente->create($data);

        return redirect()->route('expedientes.index');
    }

    public function registraAnticipo(Request $request, int $id)
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
       $mov->tipo = 'Anticipo';
       $mov->idTipo = 2;
       $mov->monto = $request->monto;
       $mov->rfc = $request->rfc;
       $mov->idExpediente = $request->id;
       $mov->fechaPago = date("Y-m-d\TH:i:s", strtotime(date('Y-m-d H:i:s')));
       $mov->uidPago = $formaDePago;
       $mov->agente  = $request->agente_aduanal;
       $mov->save();

       return redirect('expedientes/'.$id);

      

    }

    public function delete_expediente($action, $id)
    { 
        $expediente = $this->expediente->getExpedienteById($id);

        if ($action=='view') {
            return View::make('expediente.delete_expediente',['expediente'=>$expediente]);
        } elseif ($action=='delete'){
        Expediente::destroy($id);
          return redirect()
            ->back()
            ->with('message','Expediente Borrado.');
        }
        
    }

    public function estado_cuenta_v2($id,$tipo_estado)
    {
        $GetPagosAnticipos = new GetPagosAnticipos;



        //->Join('movimiento_agentes','movimiento_agentes.id_factura','=','facuras_cargadas.id')
    

         $resultado=[];
         $facturas = FacturasCargadas::where('id_expediente',$id)->get();
         foreach ($facturas as $factura) {

            $movimientosFacturas[]  = MovimientoAgente::where('idExpediente',$id)->where('id_factura',$factura->id)
            ->select('id_factura','totalPago',DB::raw('sum(montoPagado) as montoPagado'))
            ->groupBy('totalPago','id_factura')->get();

            //realizo la suma de todos los movimientos donde no tenga asignada una factura
            $totalPagosSinAsignar  = MovimientoAgente::where('idExpediente',$id)
            ->whereNull('id_factura')
            ->sum('montoPagado');
            //si la suma del total de PagosSinAsignar se encuentra en una factura
            $totalFacturaExistMov = FacturasCargadas::where('id_expediente',$id)->where('id',$factura->id)->get()->contains('total',$totalPagosSinAsignar);
            
            
            if ($totalFacturaExistMov) {
        
               $resultado[] = FacturasCargadas::where('id_expediente',$id)->where('id',$factura->id)->get()->map(function ($factura) {
                $factura['status'] = 'Hay movimientos Por aplicar';

                return $factura;
                });

        
                 
            }elseif(count($movimientosFacturas) > 0){
                $resultado[]= FacturasCargadas::where('id_expediente',$id)
                    ->where('id',$factura->id)
                    ->get()
                    ->map(function($factura, $movimientosFacturas) {
                    $factura['status'] = 'Hay anticipos';
                    $factura['movimientos'] = $movimientosFacturas;
                    return $factura;
                });
            }
         }

        return view('welcome',['resultado' => $resultado]);
        //$factura->contains('toal','500');
         //->leftJoin('movimiento_agentes','movimiento_agentes.id_factura','=','facturas_cargadas.id')
         //->select('movimiento_agentes.*','facturas_cargadas.total')


/*
        $pagosAnticipos = Movimiento::where('idExpediente',$id)->get();

        $GetPagosAnticipos->saldoTotal($totalApagar,$pagosAnticipos);
  */      
    }

    public function estado_cuenta($id, $tipo_estado)
    { 
        $result='';

        $facturasCargadas = $this->expediente->pagosFacturasEstCta($id);

        $pedimentos = Pedimento::where('expediente_id', $id)->get();
        if (count($pedimentos)==0) {
            $pedimentos= array();
            $pedim_total = '0';
            $cove_total  = '0';
        }else{
            $pedimentos  = json_decode($pedimentos['0']['json'],true); 
            // extraigo el valor total del cove y del pedimento
            // $pedim_total = $pedimentos['cuadro_liquidacion']['4']['total'];
            // $cove_total  = $pedimentos['valor_cove']['valor_aduana'];
        }

        $cove = Cove::where('id_expediente', $id)->get();
        if (count($cove) == 0) {
            $cove = array();
        }else{
            $cove = json_decode($cove['0']['json_cove'],true);
        }


        $pagos = Movimiento::where('idExpediente', $id)->where('idTipo', 1)->get();

        if (count($facturasCargadas) > 0) {
            foreach ($facturasCargadas as $fac) {
                $facturas = json_decode($fac->json_cfdi,true);
                $registros[]  = array(
                    'emisor_rfc'     => $facturas['Emisor']['Rfc'],
                    'fecha'          => $facturas['cfdiComprobante']['Fecha'],
                    'subtotal'       => $facturas['cfdiComprobante']['SubTotal'],
                    'importe'        => $facturas['Traslado']['Importe'],
                    'total'          => $fac->total_factura,
                    'pago'           => $fac->monto_pagado,
                    'saldo'          => $fac->saldo,
                    'tipo_factura'   => $fac->tipo_factura,
                    'status_factura' => $fac->status_factura,
                    'json_cfdi'      => $fac->json_cfdi,
                    'id'             => $fac->id,
                    'id_mov'         => $fac->id_mov
                );
            }

            //realizo la suma de los archivos
            $total = array(
                'subtotal' => array_sum(array_column($registros, 'subtotal')),
                'importe'  => array_sum(array_column($registros, 'importe')),
                'total'    => array_sum(array_column($registros, 'total')),
                'pago'     => array_sum(array_column($registros, 'pago')),
                'saldo'    => array_sum(array_column($registros, 'saldo')),
                // 'pedim_total' => $pedim_total,
                //'cove_total'  => $cove_total,
                //'status_saldo'=> array_sum(array_column($registros, 'saldo')),
            );

            //valido si hay saldo a favor           
            $result = $this->valida_pago($id, $total);
        } else {
            $registros = array();
            $total = array();
        }

        if ($tipo_estado == 'general') {
            return View::make('expediente.estado_cta',[
                 'id_expediente' => $id,
                 'facturas'      => $registros,
                 'pedimentos'    => $pedimentos,
                 // 'pagos'      => $pagos,
                 'total'         => $total,
                 'message'       => $result
            ]);
        } elseif($tipo_estado == 'agente') {
            return View::make('expediente.estado_cta_agente',[
                'id_expediente' => $id,
                'facturas'      => $registros,
                'factura'       => $facturas,
                'pedimentos'    => $pedimentos,
                // 'pagos'      => $pagos,
                'total'         => $total,
                'message'       => $result,
                'agente'        => $facturas['Emisor']['Nombre'],
                'agenterfc'     => $facturas['Emisor']['Rfc']
            ]);
        } elseif($tipo_estado == 'impuestos') {
            return View::make('expediente.estado_cta_impuestos',[
                'id_expediente' => $id,
                'facturas'      => $registros,
                'pedimentos'    => $pedimentos,
                // 'pagos'      => $pagos,
                'total'         => $total,
                'message'       => $result
            ]);
        }
    }

    public function valida_pago($id, $total){
        //realizo la suma de las facturas asignadas al expediente
        $suma_facturas = FacturasCargadas::where('id_expediente', $id)
            ->sum('total');
        //extraigo el monto pagado a la cuenta de gastos del agente
        $movimientoCtaGastos =Movimiento::where('idExpediente', $id)
            ->leftJoin('facturas_cargadas','movimientos.id_facturacargada','=','facturas_cargadas.id')
            ->where('idTipo', 1)  //del tipo Pago       
            ->where('tipo_factura','cta_gastos') //que la factura sea del tipo cta_gastos
            ->sum('monto_factura');

        $movimientosComprobantes = DB::table('facturas_cargadas')
            ->leftJoin('movimientos','movimientos.id_facturacargada','=','facturas_cargadas.id')
            ->select('facturas_cargadas.tipo_factura','json_cfdi','total as total_factura','monto_pagado','facturas_cargadas.id','movimientos.id as id_mov','facturas_cargadas.status_factura',
                DB::raw('total - monto_factura as saldo'),
                DB::raw('case 
                    when total - monto_factura IS NULL then "Sin movimiento"
                    else "Con movimiento"
                        end as status_mov'))
            ->where('id_expediente', $id)
            ->get();

        foreach ($movimientosComprobantes as $row) {

            if ($row->status_mov=="Sin movimiento") {

                //echo $row->total_factura.'<--'.$movimientoCtaGastos.'-->'. ($movimientoCtaGastos - $row->total_factura).'<br>';
            }
            
        }

        $facturasCargadas = $this->expediente->pagosFacturasEstCta($id);
        if (count($facturasCargadas)>0) {
            foreach ($facturasCargadas as $fac) {
                $facturas = json_decode($fac->json_cfdi,true);
                $total_facturas[]  = array(
                    'emisor_rfc'     => $facturas['Emisor']['Rfc'],
                    'fecha'          => $facturas['cfdiComprobante']['Fecha'],
                    'subtotal'       => $facturas['cfdiComprobante']['SubTotal'],
                    'importe'        => $facturas['Traslado']['Importe'],
                    'total'          => $fac->total_factura,
                    'pago'           => $fac->monto_pagado,
                    'saldo'          => $fac->saldo,
                    'tipo_factura'   => $fac->tipo_factura,
                    'status_factura' => $fac->status_factura,
                    'json_cfdi'      => $fac->json_cfdi,
                    'id'             => $fac->id,
                    'id_mov'         => $fac->id_mov
                );
            }
        } else {
            $total_facturas = array();
        }
            //valido que el saldo total de las facturas sea menor al monto de movimiento de cta de gastos
            //tambien se hace la validacion que la suma del total de las facturas restantes sea igual al monto de movimiento de cta de gastos
            if ($total['saldo'] < $movimientoCtaGastos && $movimientoCtaGastos == $suma_facturas) {
               $message  = "hay saldo a favor, desea realizar la aplicacion del gasto a todas las facturas.";
              
            }elseif($total['saldo'] < $movimientoCtaGastos  && $total['saldo']!=0){
                $message = "hay saldo a favor, desea realizar la aplicacion del gasto.>";
            }elseif($total['saldo']==0){
                $message = "";
            }else{
                 $message = "";
            }

            return $message;

    }

    public function aplicacionGasto(){
        $ids = $request->input('ids');
    }

    public function aplicarPagoCreate(Request $request, $id){

        $ids           = $request->input('ids');
        $id_cta_gastos = $request->input('id_cta_gastos');

        $formaDePago   ='aplicacion de gasto';
        $ids           = explode(',',str_replace(' ','',$ids));
        $data          = FacturasCargadas::where('id_expediente',$id)->whereIn('id', $ids)->get();
        foreach ($data as $row) {
            $mov = new Movimiento;
            
            $mov->tipo              = 'Pago';
            $mov->idTipo            = 1;
            
            $mov->monto_pagado      = $row->total; 
            $mov->monto_factura     = $row->total; 
            $mov->rfc               = $row->emisor_rfc;
            $mov->idExpediente      = $id;

            $mov->fechaPago         = date("Y-m-d\TH:i:s", strtotime(date('Y-m-d H:i:s')));
            $mov->uidPago           = $formaDePago;
            $mov->id_agencia        = $row->id_agente;
            $mov->id_empresa        = Session::get('id');
            $mov->id_facturacargada = $row->id;
            
            $respuesta = $this->registrarPagoWS($row->id);

            $resp         = $respuesta->getBody(); 
            $codeResponse = $respuesta->getStatusCode();  
            if ($codeResponse==200) {
                //$string = '{result: true, mensaje: null, interno: 1, poliza:1, tipo: PE}';
                $string = str_replace('{','{"',$resp);
                $string = str_replace('}','"}',$string);
                $string = str_replace(':','":"',$string);
                $string = str_replace(', ','","',$string);
                $datos  = json_decode($string);

                $interno = $datos->interno;
                $poliza  = $datos->poliza;
                $tipo    = $datos->tipo;
            } else {
                $interno = '';
                $poliza  = '';
                $tipo    = '';
            }

            $mov->polizaContable    = $tipo.' '.$poliza;

            if ($mov->save()) {
                FacturasCargadas::where('id', $row->id)
                ->update(['status_factura'=> 'Pagado']);

                DB::table('movimientos')
                ->where('id', $id_cta_gastos)
                ->update(['monto_pagado' => DB::raw('monto_pagado - '.$row->total)]);


            } 
        }




       return redirect()->back();

    }
 
    public function registrarPagoWS($id)
    {
        $fecha = date("Y-m-d");
        $factura   = FacturasCargadas::where('id', $id)->get();
        $json_cfdi = json_decode($factura[0]['json_cfdi'],true);

        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            'https://www.cpavision.mx/cpareview/cpa/cx/documentos_cap2/ws_pagos_anticipos.php',[
                'body'=> [
                    //PAGO PESOS
                    'asiento'        =>'505',//:505                                DOL 506, MN 505
                    'cambio'         => '',
                    'ctabanco'       => '102.01',
                    'documento'      => '12',//:12
                    'fecha2'         => $fecha,//:2017-07-01
                    'fechaV'         => $fecha,//:2017-07-01
                    'importe'        => $factura[0]['total'],//:1224
                    'modulo'         => '2',//:2                                1 CXC, 2 CXP
                    'moneda'         => '0',//:0
                    'nombre'         => $factura[0]['emisor_nombre'],
                    'nomContrato'    => $json_cfdi["cfdiComprobante"]["tipoDeComprobante"],
                    'serie'          => $json_cfdi["cfdiComprobante"]["folio"],
                    'tipo_documento' => '7',//:7                        DOL 8,  MN 7
                    'rfc_empresa'    => $factura[0]['receptor_rfc'],//:DPM140627I40
                    'rfc_proveedor'  => $factura[0]['emisor_rfc']//:FHI8704277E9 emisor
                ]
            ]
        );
        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filtro_expedientes(Request $request){
        $data = $request->all();
        $expedientes = null;
        if(!empty($data)){
            $expedientes = DB::table('expedientes')
                ->whereBetween('created_at',[
                    $data['inicio'].' 00:00:00', $data['final'].' 23:59:59'
                ])->get();
        }
        if(!isset($_REQUEST['descargar'])){
            return view('expediente.expedientes', [
                'expedientes' => $expedientes
            ]);
        }else{
            $exp = [];
            foreach ($expedientes as $expediente){
                $exp[] = $expediente->id;
            }
            $this->descarga_expedientes($exp);
        }
    }

    public function showCargarDocumentos(){
        return view('home.cargar_documentos');
    }

    public function descarga_expedientes($expedientes)
    {
        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
            ->where('configuracion','folder_storage')
            ->first();

        $rfc = $folderEmpresa->value;
        $folderZip = storage_path('app/zips/'.$rfc.'.zip');
        $zipper = new \Chumper\Zipper\Zipper;

        $zipper->make($folderZip);
        $archivos = $zipper->listFiles();
        foreach ($archivos as $archivo){
            $zipper->remove($archivo);
        }
        foreach ($expedientes as $expediente){
            $zipper->folder($expediente)->add(storage_path('app/'.$rfc.'/'.$expediente));
        }
        $logFiles = $zipper->listFiles();
        $texto = 'Detalle del contenido del archivo ZIP'.PHP_EOL.PHP_EOL;
        foreach ($logFiles as $logFile) {
            if($logFile != false)
                $texto = $texto.$logFile.PHP_EOL;
        }
        $zipper->folder('Contenido')->addString('contenido.txt', $texto);
        $zipper->close();

        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$rfc.zip");
        header("Pragma: no-cache");
        header("Content-Length: " . filesize(storage_path("app/zips/$rfc.zip")));
        readfile(storage_path("app/zips/$rfc.zip"));
    }

    /**
     * @param $id_expediente
     */
    public function cargaDocumentos($id_expediente){
        return view('documentos.cargar', compact('id_expediente'));
    }
}
