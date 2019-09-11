<?php
namespace App\Http\Controllers;

use App\Empresa;
use App\Expediente;
use App\Pedimento;
use App\PedimentosAsignado;
use App\Repositories\EloquentEmpresaRepository;
use App\Repositories\EloquentPedimentoRepository;
use App\Repositories\EloquentPedimentoExternalRepository;
use App\Services\Ctrade;
use DB;
use Excel;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Input;
use phpDocumentor\Reflection\DocBlock\Description;
use Session;
use View;

/**
 * Class PedimentoController.
 */
class PedimentoController extends Controller
{
    /**
     * @var Ctrade
     */
    protected $ctrade;

    /**
     * @var IPedimentoRepository
     */
    protected $pedimento;

    /**
     * @var IPedimentoExternalRepository
     */
    protected $pedimentoExternal;

    /**
     * @var IEmpresaRepository
     */
    protected $empresa;

    /*
     * @var id de pedimento
     */
    protected $id_pedimento;

    /**
     * PedimentoController constructor.
     *
     * @param Ctrade                       $ctrade
     * @param IPedimentoRepository         $pedimento
     * @param IEmpresaRepository           $empresa
     * @param IPedimentoExternalRepository $pedimentoExternal
     */
    public function __construct(Ctrade $ctrade, EloquentPedimentoRepository $pedimento, EloquentEmpresaRepository $empresa, EloquentPedimentoExternalRepository $pedimentoExternal)
    {
        $this->ctrade = $ctrade;
        $this->pedimento = $pedimento;
        $this->pedimentoExternal = $pedimentoExternal;
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $pedimentos = $this->pedimento->findByIdEmpresa();

        return View::make('pedimento.index',['pedimentos'=> $pedimentos]);
    }

    /**
     * @return mixed
     */
    public function reporte()
    {
        $pedimentos = null;

        return view('pedimento.pedimentos', [
            'pedimentos' => $pedimentos
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function consulta(Request $request){
        $data = $request->all();

        //dd($data);
        $pedimentos = '';
        if($data!=null){
            $pedimentos = $this->pedimento->pedimentosPeriodo(
                Session::get('id'),
                $data['ejercicio'],
                $data['periodo']
            )->paginate(15);
        }

        return View::make('pedimento.pedimentos', [
            'pedimentos' => $pedimentos,
            'ejercicio' => $data['ejercicio'],
            'periodo' => $data['periodo']
        ]);
    }

    /**
     * @return mixed
     */
    public function matchFacreview()
    {

        $pedimentos = $this->pedimentoExternal->facReviewInCt(Session::all(), true);
        $total      = $this->pedimentoExternal->totalPedimentosFr(Session::all());
        $totalSi    = $this->pedimentoExternal->totalPedimentosEncontrados(Session::all());
        $totalNo    = $this->pedimentoExternal->totalPedimentosNoEncontrados(Session::all());

        return view('pedimento.facreview', [
            'pedimentos' => $pedimentos,
            'title' => 'Pedimentos encontrados FacReview Vs Customs & Trade',
            'totales' => (object) [
                'total' => $total,
                'totalSi' => $totalSi,
                'totalNo' => $totalNo,
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function noMatchFacreview()
    {
        $pedimentos = $this->pedimentoExternal->facReviewNotInCt(Session::all(), true);
        $total = $this->pedimentoExternal->totalPedimentosFr(Session::all());
        $totalSi = $this->pedimentoExternal->totalPedimentosEncontrados(Session::all());
        $totalNo = $this->pedimentoExternal->totalPedimentosNoEncontrados(Session::all());

        return view('pedimento.facreview', [
            'pedimentos' => $pedimentos,
            'title' => 'Pedimentos no encontrados FacReview Vs Customs & Trade',
            'totales' => (object) [
                'total' => $total,
                'totalSi' => $totalSi,
                'totalNo' => $totalNo,
            ],
        ]);
    }

    /**
     * @param $formato
     */
    public function noMatchFacreviewExport($formato)
    {
        if ($formato == 'excel') {
            $pedimentos = $this->pedimentoExternal->facReviewNotInCt(Session::all(), false);
            Excel::create('FacReviewVsCT', function ($excel) use ($pedimentos) {
                $excel->sheet('Detalle no encontrados', function ($sheet) use ($pedimentos) {
                    $sheet->loadView('pedimento.facreviewexcel')
                    ->with('pedimentos', $pedimentos);
                });
            })->download('xlsx');
        }
    }

    public function cargaPedimentos()
    {
        $filename = 'M1714758.311';
        $contents = File::get($filename);
        $data = explode("\r\n",$contents);
        dd(json_encode($data));
    }

    public function asigna_pedimentos($id_pedimento,$id_expediente)
    {
        $asignarPedim = new PedimentosAsignado;
        $asignarPedim->id_expediente = $id_expediente;
        $asignarPedim->id_pedimento  = $id_pedimento;



        $pedimento = $this->pedimento->PedimReturnJson($id_pedimento);

        //dd($pedimento);

        /*foreach ($pedimento["datos_pedimento"] as $key => $value) {
            var_dump($value);

        }*/


        $id_aduana = $pedimento["datos_pedimento"][0]["id_aduana"];


        if ($asignarPedim->save()) {

            $updatePedimento = $this->pedimento->asignarPedimento($id_pedimento,$id_expediente,$id_aduana);


            $pedimento = $this->pedimento->getPedimentoEmpresa($id_pedimento);

            if (!Storage::exists($pedimento->rfc.'/'.$pedimento->expediente_id.'/pedimentos/')){
                Storage::makeDirectory($pedimento->rfc.'/'.$pedimento->expediente_id.'/pedimentos/',0777,true);
            }


            Storage::move($pedimento->rfc.'/pedimentos/'.$pedimento->archivoM,$pedimento->rfc.'/'.$pedimento->expediente_id.'/pedimentos/'.$pedimento->archivoM);
            //dd($pedimento->rfc.'/'.$pedimento->expediente_id.'/pedimentos/');

/*
            $files  = Storage::allFiles($pedimento->rfc);
            dd($files);*/

            //dd($path);

            if(Storage::exists($pedimento->rfc.'/pedimentos/'.$pedimento->archivoPDF)){
                Storage::move($pedimento->rfc.'/pedimentos/'.$pedimento->archivoPDF, $pedimento->rfc.'/'.$pedimento->expediente_id.'/pedimentos/'.$pedimento->archivoPDF);

            }




            //Storage::move($expediente->

            return redirect('expedientes/'.$id_expediente);
            
        } else {
            return redirect('expedientes/'.$id_expediente);
        }
    }

    public function pedimento_vista($id, $expediente_id)
    {
        //busco un pedimento por id expediente
        $pedimento = $this->pedimento->findByIdExpediente($id, $expediente_id);
        
        //dd($pedimento);
        if (empty($pedimento)) {
            $pedimento=$this->pedimento->findById($id);
        } 
        $pedimento= json_decode($pedimento->json, true);

        return View::make('pedimento.pedimento',['pedimento' => $pedimento,'expediente_id' => $expediente_id]);
    }

    public function pedimento($pedimento, $ejercicio, $periodo){
        $pedimento = $this->pedimento->findByPedimento($pedimento);

        $pedimento_json = json_decode($pedimento->json, true);
        return view('pedimento.pedimento', ['pedimento' => $pedimento_json, 'ejercicio'=>$ejercicio, 'periodo' => $periodo]);
    }

    public function cargar_pedimento($id_empresa)
    {
        $expedientes = Expediente::where('empresa_id','=',$id_empresa)
                     ->whereIn('status', ['Abierto','Proceso'])->get();

        return view('pedimento.cargar', [
            'expedientes'=> $expedientes,
            'id_empresa'=>$id_empresa
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload_pedimento(Request $request, $id)
    {
        // validar archivos y extension
        $this->validate($request, [
            'pedim_file_m' => 'required',
            'pedim_file_pdf' => 'required|mimetypes:application/pdf'
        ], [
            'pedim_file_m.required' => 'El archivo M es requerido',
            'pedim_file_pdf.required' => 'El archivo PDF es requerido',
            'pedim_file_pdf.mimetypes' => 'El archivo no es un PDF '
        ]);

        // crear input con la variable para validar nombre del archivo
        $request->request->set('filem',$request->file('pedim_file_m')->getClientOriginalName());

        // validar que sea una archivo m
        $this->validate($request, [
            'filem' => 'required|regex:/(^m\d{7}\.\d{3}$)/u'
        ], [
            'filem.regex' => 'El archivo ingresado no se detecto como archivo M'
        ]);

        $id_expediente = $request->input('expediente');
        $inputs        =['pedim_file_m','pedim_file_pdf'];
        $empresa       = Empresa::where('id',session()->get('id'))->first();

        foreach($inputs as $input){
            if($request->file($input)) {

                $file     = $request->file($input);
                $fileName = $file->getClientOriginalName();
                $mime     = $file->getClientMimeType();

                //extraigo la primera letra par avalidar que es un archivo M
                $fileM = substr($fileName , 0, 1);
                $fileM = strtoupper($fileM);
                if ($fileM == 'M' && strcmp($mime,"application/pdf") && strcmp($mime,"text/pdf")){
                    /*$this->nombreArchivo = $fileName;*/

                    $path = $empresa->rfc.'/pedimentos/'.$fileName;
                    if(!empty($id_expediente)){
                        $path = $empresa->rfc.'/'.$id_expediente.'/pedimentos/'.$fileName;
                    }

                    Storage::put($path, file_get_contents($request->file($input)->getRealPath()));

                    // Obtiene el json que se guardara en la base de datos
                    $pedimentos_json = $this->pedimento_json(storage_path('app/'.$path));


                    foreach ($pedimentos_json['datos_pedimento'] as $pedimento_json) {
                        $pedim = new Pedimento();
                        $pedim->empresa_id     = Session::get('id');
                        $pedim->pedimento      = $pedimento_json['num_pedimento'];
                        $pedim->aduanaDespacho = $pedimento_json['id_aduana'];
                        $pedim->impExpNombre   = $pedimento_json['nombre_imp_exp'];
                        $pedim->tipoOperacion  = $pedimento_json['tipoOperacion'];
                        $pedim->expediente_id  = $id_expediente;
                        $pedim->archivoM       = $fileName;
                        $json_pedimento= json_encode($pedimentos_json);
                        $pedim->json  = $json_pedimento;
                        $pedim->save();
                        // obtenemos el id con el que se guardo el registro
                        $id_pedimento = $pedim->id;
                        $this->id_pedimento = $id_pedimento;
                        if(!empty($id_expediente)){
                            $asignarPedim = new PedimentosAsignado;
                            $asignarPedim->id_expediente = $id_expediente;
                            $asignarPedim->id_pedimento  = $id_pedimento;
                            $asignarPedim->save();
                        }
                    }
                }elseif(!strcmp($mime,"application/pdf") || !strcmp($mime,"text/pdf")){
                    $path = $empresa->rfc.'/pedimentos/'.$fileName;
                    if(!empty($id_expediente)){
                        $path = $empresa->rfc.'/'.$id_expediente.'/pedimentos/'.$fileName;
                    }

                    Storage::put($path, file_get_contents($request->file($input)->getRealPath()));
                    $pedimento = Pedimento::find($this->id_pedimento);
                    $pedimento->update(['archivoPDF' => $fileName]);
                }
            }
        }

        if(!empty($id_expediente)) {
            $notificacion = array(
                'mensaje' => 'Carga realizada exitosamente',
                'alert-type' => 'success'
            );
            return view('documentos.cargar', compact('id_expediente'))->with('operacion_pedimento',$notificacion);
        }

        //$file->move($path, $fileName);
        return redirect('pedimento')
            ->with('message','Se cargo el pedimento exitosamente');
    }

    protected function fecha_pedimento($fecha)
    {
        $dia=substr($fecha,0,2) ;
        $mes=substr($fecha,2,2);
        $anio=substr($fecha,-4);
        $date =  $anio.'/'.$mes.'/'.$dia;
        $date=date('Y-m-d',strtotime($date));

        return ($date);
    }

    public function pedimento_json($path){
        // Abriendo el archivo
        // Abriendo el archivo
        $archivo  = fopen($path, "r");
        $numlinea = 0;
        $array    = array();
        // Recorremos todas las lineas del archivo
        while(!feof($archivo)){
            // Leyendo una linea
            $traer = fgets($archivo);
            $id = substr($traer,0,3);
            //$id = nl2br(substr($traer,0,3));
            //identifico las keys del array
            $array[$id]=array();
        }

        // Cerrando el archivo
        fclose($archivo);

        
        $file = fopen($path, "r");
        while(!feof($file)){
            // Leyendo una linea
            $traer = fgets($file);
            $id    = nl2br(substr($traer,0,3));
            if (array_key_exists($id, $array)) {
                $array_add =$traer;
                array_push($array[$id], $array_add);
            }
        }
        fclose($file);

        $collection=collect($array);
     
        $datoCove = $collection->only('505');
      
        foreach ($datoCove[505] as $key => $value) {
           //$validate = collect(explode('|',$value))->contains('12672.15');
           //if($validate){
                $columna=collect(explode('|',$value));
                $datos_cove[] = array(
                     'num_pedimento'       => $columna[1],
                     'fecha_cove'          => $columna[2],
                     'cove'                => $columna[3],
                     'incoterm'            => $columna[4],
                     'moneda_fact'         => $columna[5],
                     'valorTotalDollar'    => $columna[6],
                     'valorTotalMoneda'    => $columna[7],
                     'pais'                => $columna[8],
                     'id_fiscal'           => $columna[10],
                     'nombre_proveedor'    => $columna[11],
                     'direccion_proveedor' => $columna[12].' '. $columna[13].', '. $columna[14].', '. $columna[15].' '. $columna[16],
                );

                $datos_pedimento['datos_cove'] =  $datos_cove;

          // }
        }

        //extraigo el num_pedimento para realizar la busqueda en los demas valores
        //$num_pedimento = $datos_pedimento['datos_cove']['num_pedimento'];

        $datoPedimento = $collection->only('501');
        foreach ($datoPedimento[501] as $key => $value) {
          // $validate = collect(explode('|',$value))->contains($num_pedimento);
           //if($validate){
                $columna=collect(explode('|',$value));
                          $dato_pedimento[] = array(
                            'num_pedimento'     =>$columna[2],
                            'id_aduana'         =>$columna[3],
                            'tipoOperacion'     =>$columna[4],
                            'cve_pedimento'     =>$columna[5],
                            'rfc_importador'    =>$columna[8],
                            'tipo_cambio'       =>$columna[10],
                            'peso_bruto'        =>$columna[16],
                            'salida'            =>$columna[17],
                            'entrada'           =>$columna[18],
                            'entrada_salida'    =>$columna[19],
                            'destino_origen'    =>$columna[20],
                            'nombre_imp_exp'    =>$columna[21],
                            'direccion_imp_exp' =>$columna[22].' '.$columna[23].' '.$columna[24].' '.$columna[25].' '.$columna[26].' '.$columna[27].' '.$columna[28],
                 );

                $datos_pedimento['datos_pedimento'] =  $dato_pedimento;

          // }
        }

        //dd($datos_pedimento);

        return ($datos_pedimento);

        //$json_pedimento= json_encode($datos_pedimento);
        //$json_pedimento = json_decode($json_pedimento, true);
        //dd($json_pedimento);
    }

    /*public function pedimentosXML($pedimento, $expediente_name, $empresa){*/
    /*$path = $empresa.'/'.$expediente_name.'/pedimentos/'.$pedimento;*/
    public function pedimentosXML($pedimento_id){
        $empresa_id = session()->get('id');
        $empresa = Empresa::where('id', $empresa_id)->first();
        $pedimento = Pedimento::find($pedimento_id);
        $expediente_id = $pedimento['expediente_id'];
        $pedimento_name = $pedimento['archivoM'];

        $path = $empresa['rfc'].'/'.$expediente_id.'/pedimentos/'.$pedimento_name;

        $content = Storage::get($path);
        $mime = Storage::mimeType($path);

        return (new Response($content, 200))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'attachment; filename='.$pedimento_name);
    }

    /**
     * @param $pedimento_id
     * @return $this
     */
    public function pedimentoPDF($pedimento_id){
        $empresa_id = session()->get('id');
        $empresa = Empresa::where('id', $empresa_id)->first();
        $pedimento = Pedimento::find($pedimento_id);
        $expediente_id = $pedimento['expediente_id'];
        $pedimento_name = $pedimento['archivoPDF'];

        $path = $empresa['rfc'].'/'.$expediente_id.'/pedimentos/'.$pedimento_name;

        $content = Storage::get($path);
        $mime = Storage::mimeType($path);

        return (new Response($content, 200))
            ->header('Content-Type', $mime)
            ->header('Expires','0')
            ->header('Cache-Control','must-revalidate')
            ->header('Pragma','public');
    }
}

