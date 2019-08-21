<?php

namespace App\Http\Controllers;

use App\datosConexionEmpresa as DataConexion;
use App\Empresa;
use App\Library\PedimentoUpload;
use App\Library\SFTP\SaveSFTP;
use App\Library\SSH\SshServer;
use Collective\Remote\RemoteFacade as SSH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;

class JobUploadPedimentoController extends Controller
{
	protected $sftp;
    protected $carpetas;

    public function index(){
        $sftp  = DataConexion::where('id_empresa', Session::get('id'))->get()->first();


        if(!empty($sftp)){
            return view('sftp.index', ['sftp' => $sftp]);
        } else {
            return redirect('programacion_pedimento/create');
        }
    }

    public function create(){
		$sftp = DataConexion::where('id_empresa', Session::get('id'))->get()->first();
		if(empty($sftp)){
		    return view('sftp.create');
        } else {
		    return redirect('programacion_pedimento');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(Request $request){
		$empresa_id = Session::get('id');
		$data = $request->all();

        $conection = new DataConexion();
        $conection->host = $data['host'];
        $conection->user = $data['user'];
        $conection->id_empresa = $empresa_id;
        $conection->password = base64_encode($data['password']);
        $conection->path = $data['path'];
        $conection->save();

        //return redirect()->back()->with('info','Se ha recibido sus datos correctamente');
		return redirect()->back();
	}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function edit($id){
        $sftp = DataConexion::find($id);
        return view('sftp.edit', ['sftp' => $sftp, 'url'=>'/programacion_pedimento/'.$id, 'method'=>'PUT']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id){
	    $data = $request->all();
        $sftp = DataConexion::find($id);
        $sftp->host = $data['host'];
        $sftp->user = $data['user'];
        $sftp->path = $data['path'];
        if ($sftp->save()){
            return redirect()->route('programacion_pedimento.index');
        }
    }

    public function changePassword(){
        return view('sftp.password');
    }

    public function updatePassword(Request $request){
        $data = $request->all();
        $empresa_id = Session::get('id');
        $sftp = DataConexion::where('id_empresa', $empresa_id)->first();
        $contrasenia = base64_decode($sftp->password);
        if($contrasenia === $data['old_password']){
            DataConexion::where('id_empresa', $empresa_id)
                ->update(['password' => base64_encode($data['password'])]);
        } else {
            return redirect()->withErrors('La contraseña no coincide', 'password');
        }

    }

    public function uploadPedimento()
    {
        $pedimento = new PedimentoUpload;
        //Listamos el directorio de la carpeta Pedimentos.
        $foldersRFC  = Storage::disk('pedimentos')->directories('/');
        //Funcion para remover las carpetas que no voy a utilizar
        $foldersRFC  = $this->remover("procesados", $foldersRFC);
        foreach ($foldersRFC as $key => $folderRFC) {
            $getEmpresaByRFC = Empresa::getEmpresaByRFC($folderRFC);
            //Me posiciono dentro de la Carpeta
            $files = Storage::disk('pedimentos')->files($folderRFC);
            //quito el archivo .gitignore
            unset($files['0']);
            //entro dentro de la carpeta de la empresa y empiezo a Trabajar con Los pedimentos
            foreach ($files as $key => $pathFile) {
                //guardo la ruta de mi archivo
                $path = storage_path('pedimentos/').$pathFile;
                //obtengo solo el nombre de mi archivo
                $file = str_replace($folderRFC.'/','', $pathFile);

                $pedimento->upload_pedimento($file,$path,$getEmpresaByRFC->id);
                Storage::disk('pedimentos')->copy($pathFile, $folderRFC.'/procesados/'.$file);
                Storage::disk('pedimentos')->delete($pathFile);
                echo "se ha cargado los pedimentos a la base de datos: ".$pathFile."<br>";
            }
        }
    }

    function remover ($valor, $arr)
    {
        foreach (array_keys($arr, $valor) as $key)
        {
            unset($arr[$key]);
        }
        return $arr;
    }

    /**
     * Proceso que recibe el arreglo de archivos desacargados del SFTP y
     * los procesa para guardarlos en la base de datos.
     */
    public function processFilesSFTP(Request $request){
        $storage = $request->get('arreglo');
        $rfc = $request->get('empresa');

        $file_m = 0;
        $file_m_pdf = 0;
        $file_cove = 0;

        // Obtiene la empresa
        $empresa_id = session()->get('id');
        // Metodos para guardar los archivos
        $saveSFTP = new SaveSFTP();
        foreach ($storage as $file){
            // Verifica el tipo de archivo
            if($file['type_file'] == 'm'){
                // Guarda el pedimento
                $pedimento_id = $saveSFTP->storePedimento(
                    storage_path('app/'.$rfc.'/'.$file['storage']),
                    $file['expediente_id'],
                    $empresa_id,
                    $file['file_name']
                );
                // Asigne el pedimento al expediente
                $saveSFTP->asignarPedimento($file['expediente_id'], $pedimento_id);
                $file_m++;
            } elseif($file['type_file'] == 'm_pdf'){
                // Guarda el nombre del pdf del pedimento
                $saveSFTP->storeNamePdfPedimento($file['file_name'], $file['expediente_id']);
                $file_m_pdf++;
            } elseif($file['type_file'] == 'cove'){
                // Guarda el cove
                $cove_id = $saveSFTP->storeCove(
                    $file['storage'],
                    $file['expediente_id'],
                    $file['file_name']
                );
                $saveSFTP->getCovePdf($cove_id, $file['storage'], $file['file_name']);
                $file_cove++;
            }
        }

        return response()->json([
            'status' => 200,
            'file_m' => $file_m,
            'file_m_pdf' => $file_m_pdf,
            'file_cove' => $file_cove
        ]);
    }

    public function uploadFilesSFTP(){


        $id = Session::get('id');
        $empresa = Empresa::find($id);
        $conection = DataConexion::where('id_empresa', $id)->first();
        $path = storage_path('app/').$empresa->rfc;
        $result_files = [];

        //$comando = "sftp ".$conection->user."@".$conection->host." -- ".$conection->password;


        //valida si existe la carpeta de la empresa, de lo contrario la crea
        $exists = file_exists($path);
        if (!$exists){
            mkdir($path);
        }


        try {
            // Metodo para ir al servidor y extraer una lista con los nombres de los
            // archivos encontrados
            $target = [
                'host'      => $conection->host,
                'username'  => $conection->user,
                'password'  => $conection->password,
                'timeout'   => 10,
            ];
            config([
                'remote.connections.runtime.host' => $target['host'],
                'remote.connections.runtime.username' => $target['username'],
                'remote.connections.runtime.password' => $target['password']
            ]);
            $commands = [
                'cd '.$conection->path,
                'ls'
            ];
            $this->carpetas = '';





            SSH::into('runtime')->run($commands, function($line){

                $this->carpetas = $line;
            });



            // La lista viene en un string, por eso se separa cuando encuentra un espacio
            $files = preg_split('/\s/', $this->carpetas);
            $ssh = new SshServer();

            // Metodo para recorrer la lista y buscar cuando se encuentra una carpeta o un archivo
            foreach ($files as $file){
                $result_files[] = $ssh->isFolder($conection->path, $file, $path, $file);
            }

            // Si no se encontraron archivos nuevos regresa un mensaje al usuario, indicando que
            // no hay archivos nuevos.
            $founded_files = 0;
            foreach ($result_files as $result_file) {
                if(!empty($result_file)){
                    $founded_files++;
                }
            }
            if($founded_files == 0)
                return response()->json(['msg' => 'No se encontraron archivos nuevos!']);

            // Verifica si todos los archivos se descargaron correctamente.
            $num_expedientes = 0;
            $num_subcarpetas = 0;
            $num_archivos_ok = 0;
            $num_archivos_fail = 0;
            $num_archivos_m = 0;
            $num_archivos_m_pdf = 0;
            $num_archivos_cove = 0;
            $num_archivos_cove_pdf = 0;
            $num_archivos_cove_match = 0;

            foreach ($result_files as $key => $expedientes){
                $num_expedientes++;
                foreach ($expedientes as $subcarpetas) {
                    $num_subcarpetas++;
                    foreach ($subcarpetas as $archivos){
                        if ($archivos['status'] == 200){
                            $storage[] = [
                                'type_file'       => $archivos['type_file'],
                                'storage'         => $archivos['storage'],
                                'expediente_id' => $archivos['expediente_id'],
                                'file_name'       => $archivos['file_name']
                            ];
                            $num_archivos_ok++;
                            if($archivos['type_file'] == 'm'){
                                $num_archivos_m++;
                            } elseif($archivos['type_file'] == 'm_pdf'){
                                $num_archivos_m_pdf++;
                            } elseif($archivos['type_file'] == 'cove'){
                                $num_archivos_cove++;
                            } elseif($archivos['type_file'] == 'cove_pdf'){
                                $num_archivos_cove_pdf++;
                            } elseif($archivos['type_file'] == 'cove_match'){
                                $num_archivos_cove_match++;
                            }
                        } elseif($archivos['status'] == 500) {
                            $num_archivos_fail++;
                            $errores[] = [
                                'metadata'=> $archivos['metadata']
                            ];
                        }
                    }
                }
            }

            // Si existen archivos que no se pudieron procesar, se muestra una vista con los errores
            if($num_archivos_fail > 0){
                return view('sftp.finish', compact([
                    'num_expedientes',
                    'num_subcarpetas',
                    'num_archivos_ok',
                    'num_archivos_fail',
                    'num_archivos_m',
                    'num_archivos_m_pdf',
                    'num_archivos_cove',
                    'num_archivos_cove_pdf',
                    'num_archivos_cove_match',
                    'errores'
                ]));
            }
            return response()->json(['msg' => 'Finalizo la descarga', 'status' => 200, 'empresa' => $empresa->rfc, 'storage' => $storage]);

        } catch (\Exception $e) {
            if($e->getMessage() === 'Unable to connect to remote server.'){
                $result_files = ['status' => 401 , 'metadata' => 'No se logro conectar con el servidor SFTP, revise la contraseña'];
                return $result_files;
            }

        }
    }
}