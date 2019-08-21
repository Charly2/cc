<?php

namespace App\Http\Controllers;

use App\ConfigEmpresa;
use App\Documento;
use App\Expediente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

//use Chumper\Zipper\Zipper;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Expediente $expediente)
    {
        $data =$expediente->documentos()->get();

        return response()->json($data, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDocument($expediente_id)
    {
        return view('documentos.create', compact('expediente_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $file = $request->file('files');
        $fileName = $file->getClientOriginalName();


        $folderEmpresa = ConfigEmpresa::where('empresa_id', session()->get('id'))
                        ->where('configuracion','folder_storage')
                        ->first();



        $expediente_id = $request->input('expediente_id');


        $path = $folderEmpresa->value.'/'.$expediente_id.'/Documentos_soporte/';


        $ldate = date('YmdHis').rand(1,52);
        $fileName = $ldate."_".$fileName;
        //print_r($fileName);die();

        $saved_file = Storage::put($path.$fileName, file_get_contents($file->getRealPath()));



        if( $saved_file ){

            $data_file = array('expediente_id'=>$expediente_id ,'nombreDocumento' => $fileName );

            try {
                $doc = Documento::create($data_file);

                $res['estatus'] = 'ok';
                $res['file'] = $fileName;
                echo json_encode($res);
                /*return redirect()->route('expediente.show', ['id' => $expediente_id]);*/
            } catch (Exception $e) {
                 echo $e;
            }
        } else {
            $res['estatus'] = 'error';
            $res['file'] = $fileName;
            echo json_encode($res);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$sftp   = DataConexion::where('id_empresa',Session::get('id'))->get()->first();
        $rfc = 'TME840710TR4';
        $exists = Storage::disk('public')->exists($rfc);
        if (!$exists) {
           // $msg_local = 'No existe la Carpeta Local, ¿Desea Crear una?';
            Storage::disk('public')->put($rfc.'/.gitignore', '*!.gitignore');
            Storage::disk('public')->put($rfc.'/documentos_soporte/.gitignore', '*!.gitignore');
            Storage::disk('public')->put($rfc.'/facturas/.gitignore', '*!.gitignore');
            $this->descargar_documentos($id);
        }else{
            $this->descargar_documentos($id);
        } 

        return response()->json($msg_local, 200);
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
        $data = $request->all();
        $documento = Documento::find($id);
        $documento->nota = $data['note'];
        if($documento->save()){
            return response()->json('OK');
        } else {
            // TODO: Enviar error de no actualizado.
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        $posteado = $id;
        $document = Documento::findOrFail($id);

        $folderEmpresa = ConfigEmpresa::where('empresa_id', session()->get('id'))
            ->where('configuracion','folder_storage')
            ->first();

        $file = storage_path('app\\'.$folderEmpresa->value.'\\'.$document->expediente_id.'\\Documentos_soporte\\'.$document->nombreDocumento);
        if(is_file($file)){

            unlink($file);
            if ($document->delete()){
                $success = 'delete success';
                return response()->json($success);
            } else {
                return 'delete failed';
            }
        }else {
            echo 'El directorio no existe';
        }

    }


    /**
     * Permite descargar un expediente en especifico
     *
     * @param $expediente_id
     * @throws \Exception
     */
    public function descargar_documentos($expediente_id)
    {
        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
            ->where('configuracion','folder_storage')
            ->first();

        $rfc = $folderEmpresa->value;
        $folderZip = storage_path('app/zips/'.$rfc.'.zip');
        $zipper = new \Chumper\Zipper\Zipper();

        // Genera la carpeta zip donde se guardara el contenido
        $zipper->make($folderZip);
        // Lista y borra los archivos anteriores en el zip
        $archivos = $zipper->listFiles();
        foreach ($archivos as $archivo){
            $zipper->remove($archivo);
        }

        // Guarda los archivos en el zip
        $files = storage_path('app/'.$rfc.'/'.$expediente_id);
        $zipper->add($files);
        // Genera la lista de archivos para guardar el contenido del zip
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

    public function descargar_documento($documento_id){
        $folderEmpresa = ConfigEmpresa::where('empresa_id',session()->get('id'))
            ->where('configuracion','folder_storage')
            ->first();
        $rfc = $folderEmpresa->value;

        $documento = Documento::find($documento_id);
        $expediente_id = $documento->expediente_id;
        $fileName = $documento->nombreDocumento;

        $path = $rfc . '/' . $expediente_id . '/Documentos_soporte/' . $fileName ;

        $content = Storage::get($path);
        $mime = Storage::mimeType($path);

        if($mime === 'application/pdf') {
            return (new Response($content, 200))
                ->header('Content-Type', $mime)
                ->header('Expires', '0')
                ->header('Cache-Control', 'must-revalidate')
                ->header('Pragma', 'public');
        } else {
            return (new Response($content, 200))
                ->header('Content-Type', $mime)
                ->header('Content-Disposition', 'attachment; filename='.$fileName);

        }
    }
}
