<?php

namespace App\Http\Controllers;

use App\datosConexionEmpresa as DataConexion;
use App\Empresa;
use App\Library\SFTP\SaveSFTP;
use App\Library\SFTP_NEW\SFTP_NEW;
use App\Library\SSH\SshServer;
use Illuminate\Http\Request;
use Collective\Remote\RemoteFacade as SSH;

use App\Http\Requests;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Session;
use DateTime;

class PruebaController extends Controller
{

    protected $carpetas;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sftp  = DataConexion::where('id_empresa', Session::get('id'))->get()->first();

        return view('sftp_new.index', ['sftp' => $sftp]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_get()
    {
        $id = Session::get('id');
        $empresa = Empresa::find($id);
        $conection = DataConexion::where('id_empresa', $id)->first();
        $path = storage_path('app/').$empresa->rfc;




        $adapter = new SftpAdapter([
            'host' => $conection->host,
            'port' => 22,
            'username' =>  $conection->user,
            'password' => $conection->password,
            'root' => $conection->path,
            'timeout' => 10,
            'directoryPerm' => 0755
        ]);

        $filesystem = new Filesystem($adapter);
        $adapter->connect();
        if ($adapter->isConnected()) {
            $servicioFTP = new SFTP_NEW($filesystem);

            $servicioFTP->getFilesRemote();
        }


        return $servicioFTP->getFilesList();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index_get_files()
    {
        $data = request()->all();


        $id = Session::get('id');
        $empresa = Empresa::find($id);
        $conection = DataConexion::where('id_empresa', $id)->first();
        $path = storage_path('app/').$empresa->rfc;




        $adapter = new SftpAdapter([
            'host' => $conection->host,
            'port' => 22,
            'username' =>  $conection->user,
            'password' => $conection->password,
            'root' => $conection->path,
            'timeout' => 10,
            'directoryPerm' => 0755
        ]);

        $filesystem = new Filesystem($adapter);
        $adapter->connect();
        if ($adapter->isConnected()) {
            $servicioFTP = new SFTP_NEW($filesystem);
            print_r($this->getToDownload($data['files'],$servicioFTP));
            // $servicioFTP->getFilesRemote();
        }

    }



    public function getToDownload($data,$servicioFTP){
        $files = array();
        $files['coves'] = [];
        $files['pedimento'] = [];
        $files['yaexiste'] = [];
        $date = new DateTime();
        $id = Session::get('id');
        $empresa = Empresa::find($id);




        $idal= $empresa->rfc;


        $mainRand = $date->format('Y_m_d_H_i_s');
        foreach ($data as $file){
            $ruta_o_archivo = explode('/',$file);

            $ruta_o_archivo= end($ruta_o_archivo);

            $expreg_m = '/^(m|M)(\d{7})\.(\d{3})$/';
                        // v1234-123456.pdf
                        $expreg_m_pdf = '/^(v|V)(\d{4})-(\d{6}|\d{7})\.(pdf|PDF)$/';
                        // 123456789012345_p.pdf
                        $expreg_m2_pdf = '/^\d{15}_p\.(pdf|PDF)$/';
                        // 17-1600057_C600556351.xml
                        $expreg_cove = '/^\d{2}-\d{7}_\d{9}\.(xml|XML)$/';
                        $expreg_cove2 = '/^\d{2}-\d{6}_\d{8}\.(xml|XML)$/';
                        // ACUSE_DE_VALOR_COVE17285EHM7.pdf
                        $expreg_cove_pdf = '/^ACUSE_DE_VALOR_COVE((\d|\w){9})\.(pdf|PDF)$/';
                        // RespSolCOVE_17-1600057_C600556351.xml
                        $expreg_cove_match = '/^RespSolCOVE_\d{2}-\d{7}_C\d{9}\.(xml|XML)$/';



                        if(preg_match($expreg_m, $ruta_o_archivo)) {
                            $expediente_id = $this->saveExpediente($idal,$mainRand, $idal);
                            $archivo_local = $idal.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo;

                            if(!file_exists(storage_path("app/".$archivo_local))){
                                $servicioFTP->getFileToStoreRemote($file,$archivo_local);
                                $files['pedimento'][]= $ruta_o_archivo;
                            } else {
                                $files['yaexiste'][]= $ruta_o_archivo;
                            }
                        }
                        elseif(preg_match($expreg_m_pdf, $ruta_o_archivo) || preg_match($expreg_m2_pdf, $ruta_o_archivo)) {
                            $expediente_id = $this->saveExpediente($idal,$mainRand, $idal);
                            $archivo_local = $idal.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo;
                            if(!file_exists(storage_path("app/".$archivo_local))){
                                $servicioFTP->getFileToStoreRemote($file,$archivo_local);
                                $files['pedimento'][]= $ruta_o_archivo;
                            } else {
                                $files['yaexiste'][]= $ruta_o_archivo;
                            }
                        } elseif(preg_match($expreg_cove, $ruta_o_archivo)||preg_match($expreg_cove2, $ruta_o_archivo)){
                            $expediente_id = $this->saveExpediente($idal,$mainRand, $idal);
                            $archivo_local = $idal.'/'.$expediente_id.'/coves/'.$ruta_o_archivo;
                            if(!file_exists(storage_path("app/".$archivo_local))){
                                $servicioFTP->getFileToStoreRemote($file,$archivo_local);
                                $files['coves'][]= $ruta_o_archivo;
                            } else {
                                $files['yaexiste'][]= $ruta_o_archivo;
                            }
                        } elseif(preg_match($expreg_cove_pdf, $ruta_o_archivo)){
                            $expediente_id = $this->saveExpediente($idal,$mainRand, $idal);
                            $archivo_local = $idal.'/'.$expediente_id.'/coves/'.$ruta_o_archivo;
                            if(!file_exists(storage_path("app/".$archivo_local))){
                                $servicioFTP->getFileToStoreRemote($file,$archivo_local);
                                $files['coves'][]= $ruta_o_archivo;
                            } else {
                                $files['yaexiste'][]= $ruta_o_archivo;
                            }

                        } elseif(preg_match($expreg_cove_match, $ruta_o_archivo)){
                            $expediente_id = $this->saveExpediente($idal,$mainRand, $idal);
                            $archivo_local = $idal.'/'.$expediente_id.'/coves/'.$ruta_o_archivo;
                            if(!file_exists(storage_path("app/".$archivo_local))){
                                $servicioFTP->getFileToStoreRemote($file,$archivo_local);
                                $files['coves'][]= $ruta_o_archivo;
                            } else {
                                $files['yaexiste'][]= $ruta_o_archivo;
                            }
                        } else {
                            $files['nopaso'][]= $ruta_o_archivo;
                        }


        }


        return json_encode($files);

    }

    public function saveExpediente($ruta_local,$sel, $expediente_name){
        $empresa_id = session()->get('id');
        // Metodos para guardar los archivos
        $saveSFTP = new SaveSFTP();
        // Obtiene el id del expediente, sino existe lo crea y trae el id
        $expediente_id = $saveSFTP->storeExpediente($expediente_name, $empresa_id);



        // Si no existe la carpeta del expediente, la crea
        if (!file_exists(storage_path('app/'.$ruta_local.'/'.$expediente_id))){

            mkdir(storage_path('app/'.$ruta_local.'/'.$expediente_id),0777,true);
            mkdir(storage_path('app/'.$ruta_local.'/'.$expediente_id.'/coves'),0777,true);
            mkdir(storage_path('app/'.$ruta_local.'/'.$expediente_id.'/pedimentos'),0777,true);
        }
        return $expediente_id;
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $arr = ["174730617000249/17-400175/17-400175_89038721.xml", "174730617000249/17-400175/m3061594.006", "174730617000249/17-400175/V0101-999997.pdf"];

        $adapter = new SftpAdapter([
            'host' => '187.190.74.119',
            'port' => 22,
            'username' => 'reportes',
            'password' => '123',
            'root' => '/home/test',
            'timeout' => 10,
            'directoryPerm' => 0755
        ]);

        $filesystem = new Filesystem($adapter);
        $adapter->connect();
        if ($adapter->isConnected()) {
            $servicioFTP = new SFTP_NEW($filesystem);
            print_r($this->getToDownload($arr,$servicioFTP));
           // $servicioFTP->getFilesRemote();
        }





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
}
