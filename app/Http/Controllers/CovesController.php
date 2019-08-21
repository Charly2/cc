<?php

namespace App\Http\Controllers;

use App\ConfigEmpresa;
use App\Cove;
use App\Expediente;
use App\Repositories\EloquentCoveRepository;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MrGenis\Library\XmlToArray;
use Session;

class CovesController extends Controller
{
    protected $pathCove;

    protected $filename;

    protected $coves;

    public function __construct(EloquentCoveRepository $cove){
        $this->coves = $cove;
    }

    public function index($id_empresa)
    {
        $coves=Cove::where('id_empresa','=',Session::get('id'))->get();
            return view('cove.index',['coves'=> $coves]);
    }


    public function cargar_cove($id_empresa)
    {
    	$expedientes = Expediente::where('empresa_id','=',$id_empresa)
    				 ->whereIn('status', ['Abierto','Proceso'])->get();
    	return view('cove.cargar',['expedientes'=> $expedientes,'id_empresa'=>$id_empresa]);
    }


    public function upload_cove(Request $request, $id)
    {
        // Validaciones de campos de archivos vacios
        $this->validate($request, [
            'cove_xml' => 'required',
            'cove_pdf' => 'required'
        ], [
            'cove_xml.required' => 'El archivo XML es requerido',
            'cove_pdf.required' => 'El archivo PDF es requerido'
        ]);

        //obtenemos el campo file definido en el formulario 
        $inputs =['cove_xml','cove_pdf'];
        $name_cove = $request->input('num_cove');
        $id_expediente = $request->input('expediente');

        foreach ($inputs as $input) {
            if($request->file($input)){
                $file = $request->file($input);
                // se obtiene el mimetype para validar que los tipos de archivo sean los correctos, se realiza aqui porque la funcion validate mimetype no funciona con text/xml
                $mime = $file->getClientMimeType();
                // validar el mimetype del archivo xml
                if(!strcmp($input,'cove_xml') && strcmp($mime,'application/xml') && strcmp($mime,'text/xml')){
                    return redirect()->back()->withErrors(['cove_xml'=>'El tipo de archivo ingresado no coincide con el tipo de archivo XML']);
                    // validar el mimetype del archivo pdf
                }elseif(!strcmp($input,'cove_pdf') && strcmp($mime, 'application/pdf') && strcmp($mime, 'text/pdf')){
                    return redirect()->back()->withErrors(['cove_pdf'=>'El tipo de archivo ingresado no coincide con el tipo de archivo PDF']);
                }
                // Guarda archivo y otras cosas
                $resultado = $this->save_file($request, $file, $name_cove, $id_expediente);
                //guarda los registros en la base de datos
                if(!empty($resultado)){
                    $respuesta = $this->save_info($name_cove, $resultado, $id_expediente);
                    if ($respuesta) {
                        $coves = Cove::where('id_empresa',Session::get('id'))->get();
                    }else{
                        return redirect()
                            ->back()
                            ->with('message','Error en la carga.');
                    }
                }
            }
        }
        if(!empty($id_expediente)) {
            $notificacion = array(
                'mensaje' => 'Carga realizada exitosamente',
                'alert-type' => 'success'
            );
            return view('documentos.cargar', compact('id_expediente'))->with('operacion_coves',$notificacion);
        }
        return redirect('coves/' . $id);
    }

    /**
     * Guarda los archivos del cove(cove y pdf).
     *
     * @param UploadedFile $file
     * @param $name_cove
     * @param $id_expediente
     * @return array|bool
     */
    public function save_file($request, $file, $name_cove, $id_expediente){
        // Obtiene el tipo de contenido del archivo
        $mime       = $file->getClientMimeType();

        // Obtenemos el nombre del archivo
        $fileName   = $file->getClientOriginalName();

        $datos_cove = [];

        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
                        ->where('configuracion','folder_storage')
                        ->first();

        if($id_expediente == null){
            $ruta = $folderEmpresa->value.'/coves/';
        } else {
            $ruta = $folderEmpresa->value.'/'.$id_expediente.'/coves/';
        }
        $this->pathCove = $path = storage_path("app/".$ruta.$fileName);

        // $path       = "uploads/".$name_cove.'/';

        if(!strcmp($mime,"application/xml") || !strcmp($mime,"text/xml")) {
            if ($file->isValid()) {
                Storage::put($ruta.$fileName, file_get_contents($request->file('cove_xml')->getRealPath()));
                $this->filename = explode('.',$fileName);

                //INICIO-convierto el xml en un JSON
                $xml_contenido = file_get_contents($path);
                $result = XmlToArray::convert($xml_contenido);
                $json_cove = collect($result)->tojson();

                $datos_cove = array (
                    'xml'       => $fileName,
                    'json_cove' => $json_cove
                );
            }
        } elseif(!strcmp($mime,"application/pdf") || !strcmp($mime,"text/pdf")){
            Storage::put($ruta.'/'.$this->filename[0].'.pdf', file_get_contents($request->file('cove_pdf')->getRealPath()));
        }
        return $datos_cove;
    }

    // Guarda la informacion del cove.
    public function save_info($name_cove,$datos_cove,$id_expediente){
       // $path      = "uploads/".$name_cove.'/';

       // $cove_info = json_decode($datos_cove['0']['json_cove'],true);
        $cove = new Cove();


        $exp = Expediente::where('aduana_id',$id_expediente)->get();
        dd( $exp);
        $cove->id_agente    = Session::get('id_agencia');
        $cove->id_usuario   = Session::get('id_usuario');
        $cove->id_empresa   = Session::get('id');

        if ($id_expediente) {
            $cove->id_expediente = $id_expediente;
        } 

        $cove->usr_num_cove = $name_cove;
        $cove->xml          = $datos_cove['xml'];
        $cove->json_cove    = $datos_cove['json_cove'];      
        $cove->id_fiscal    = '';//$cove_info['emisor']["identificacion"];
        // $cove->pdfs         = $path.$datos_cove['1']['pdf'];

        if($cove->save()) {
            return true;
        }
        else {
            //\File::delete($path."/".$fileName);
           return false;
        }
    }

    public function show_unsigned_cove(Request $request, $id_expediente){

        $coves = Cove::whereNull('id_expediente')->where('id_empresa','=',Session::get('id'))->get();
        
        return view('cove.unsigned',['id_expediente'=>$id_expediente,'coves'=> $coves]);

    }


    public function asigna_cove($id_cove, $id_expediente){
        // $id = Agente Aduanal
        $id = Expediente::where('id',$id_expediente)->select('agente_aduanal')->first();
 
        $coves = Cove::where('id','=',$id_cove)->first();
        if ($coves) {
            Cove::where('id', $id_cove)
            ->update(['id_expediente'=> $id_expediente,'id_agente'=>$id->agente_aduanal]);

            //Muevo el archivo de Posición
            $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
                            ->where('configuracion','folder_storage')
                            ->first();

            //File::move("storage/$folderEmpresa->value/$coves->xml",  "storage/$folderEmpresa->value/$id_expediente/$coves->xml");
            // Mueve el archivo de carpeta en Storage/{Empresa}/{id_expediente}/
            Storage::move($folderEmpresa->value.'/'.$coves->xml, $folderEmpresa->value.'/'.$id_expediente.'/'.$coves->xml);
            $archivos = explode('.',$coves->xml);
            $ruta = storage_path('app/'.$folderEmpresa->value.'/'.$archivos[0].'.pdf');
            if(file_exists($ruta)){
                Storage::move($folderEmpresa->value.'/'.$archivos[0].'.pdf', $folderEmpresa->value.'/'.$id_expediente.'/'.$archivos[0].'.pdf');
            }
            return redirect()->route('expediente.show', array('id' => $id_expediente));
        
        } else {
           echo "error";
        }

        $coves = Cove::where('id_expediente','=0')->get();
    }

    public function parseo_cove(){
        $file =url("uploads/328802_6166COVE.xml");
        $xml = simplexml_load_file($file); 
        $array = (array) $xml->ComprobanteValorElectronico ;
        $json_cove=json_encode($array);
        dd($array);
    }    

    public function cove_json(){
       
        // Abriendo el archivo
        $archivo = fopen('uploads/COVE1727I83M1/M1714758.311', "r");
        $numlinea = 0;
        $array = array();
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

        $file = fopen('uploads/COVE1727I83M1/M1714758.311', "r");
        while(!feof($file)){
            // Leyendo una linea
            $traer = fgets($file);
            $id = nl2br(substr($traer,0,3));


            if (array_key_exists($id, $array)) {
                $array_add =$traer;
                array_push($array[$id], $array_add);
            }
        }
        fclose($file);


        $json_array= json_encode($array);
        $json_array = json_decode($json_array, true);

        foreach ($json_array['510'] as $row) {
         // echo $row.'<br>';
          $fila    =  ( explode( '|', $row ) );
          $datos[] = array(
             $fila[4]
           );
        $datos_pedimento['cuadro_liquidacion'] =  $datos;

        }
        $fila_iva =  (explode( '|', $json_array['557']['0'] ) )['6'];
        array_push( $datos_pedimento['cuadro_liquidacion'] , array($fila_iva));

         foreach ($json_array['501'] as $row) {
         // echo $row.'<br>';
          $fila          =  ( explode( '|', $row ) );
          $datos_pedim = array(
             'num_pedimento'  =>$fila[2],
             'id_aduana'      =>$fila[3],
             'tipoOperacion'  =>$fila[4],
             'cve_pedimento'  =>$fila[5],
             'rfc_importador' =>$fila[8],
             'tipo_cambio'    =>$fila[10],
             'peso_bruto'     =>$fila[16],
             'salida'         =>$fila[17],
             'entrada'        =>$fila[18],
             'entrada_salida' =>$fila[19],
             'destino_origen' =>$fila[20],
             'nombre_imp_exp' =>$fila[21],
             'direccion_imp_exp' =>$fila[22].' '.$fila[23].' '.$fila[24].' '.$fila[25].' '.$fila[26].' '.$fila[27].' '.$fila[28],
             
          );
        $datos_pedimento['datos_pedimento'] =  $datos_pedim;

        }

        $fila_fechas = array( 'fecha_entrada' =>(explode( '|', $json_array['506']['0'] ) )['3'],
                        'fecha_pago' => (explode( '|', $json_array['506']['1'] ) )['3']
                        );
        //array_push( $datos_pedimento , $fila_fechas);
        $datos_pedimento['fechas'] =  $fila_fechas;

        $json_pedimento= json_encode($datos_pedimento);
        //$json_pedimento = json_decode($json_pedimento, true);
        //dd($json_pedimento);

}

    public function descargarXMLCove($xml, $expediente_id,$empresa){
        $path = $empresa . '/' . $expediente_id . '/coves/' . $xml ;

        $content = Storage::get($path);
        $mime = Storage::mimeType($path);

        return (new Response($content, 200))
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'attachment; filename='.$xml);
    }

    public function descargarPDFCove($xml,$expediente_id,$empresa)
    {
        $file = explode('.', $xml);
        $path = $empresa . '/' . $expediente_id . '/coves/' . $file[0] . '.pdf';

        $content = Storage::get($path);
        $mime = Storage::mimeType($path);

        return (new Response($content, 200))
            ->header('Content-Type', $mime)
            ->header('Expires', '0')
            ->header('Cache-Control', 'must-revalidate')
            ->header('Pragma', 'public');
    }

    public function coveFacturaShow($id_cove,$id_expediente){
        $cove = Cove::findOrFail($id_cove);
        $cove = json_decode($cove->json_cove,true);
        //dd($cove);
        return view ('cove.vista_factura',['cove'=>$cove,'id_expediente'=>$id_expediente]);
    }
}
