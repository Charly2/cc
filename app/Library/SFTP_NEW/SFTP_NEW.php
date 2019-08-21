<?php


namespace App\Library\SFTP_NEW;


use Collective\Remote\RemoteFacade as SSH;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use phpDocumentor\Reflection\Types\Array_;


class SFTP_NEW
{


    public $connetion;
    public $files ;
    /**
     * SFTP_NEW constructor.
     */
    public function __construct($connetion)
    {
        $this->connetion= $connetion;
        $this->files = Array();
    }

    public function getFilesRemote(){
        $this->listFilesOfDir('/');

        //print_r($this->files);
    }

    public function listFilesOfDir($_path){
           foreach ($this->connetion->listContents($_path) as $file){
               if ($this->isDir($file)){
                   $this->listFilesOfDir($file['path']);
               }else{
                   $this->files[] = $file['path'];
               }
           }
    }


    public function getFileToStoreRemote($file,$dir){


        $adapter = new Local(storage_path('app/'));
        $filesystem = new Filesystem($adapter);



        $content = $this->connetion->read($file);


        $response = $filesystem->write($dir,$content);



        //SSH::into('runtime')->get($file, $dir);



    }

    public function isDir($file){
        return $file['type']=="dir";

    }


    public function getFilesList(){
        $arr = array();



        foreach ($this->files as $file){
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
                $arr[] = $file;
            }
            elseif(preg_match($expreg_m_pdf, $ruta_o_archivo) || preg_match($expreg_m2_pdf, $ruta_o_archivo)) {
                if(!file_exists(storage_path("app/".$ruta_o_archivo))){
                    $arr[] = $file;
                }

            } elseif(preg_match($expreg_cove, $ruta_o_archivo)||preg_match($expreg_cove2, $ruta_o_archivo)){
                if(!file_exists(storage_path("app/".$ruta_o_archivo))){
                    $arr[] = $file;
                }
            } elseif(preg_match($expreg_cove_pdf, $ruta_o_archivo)){
                if(!file_exists(storage_path("app/".$ruta_o_archivo))){
                    $arr[] = $file;
                }
            } elseif(preg_match($expreg_cove_match, $ruta_o_archivo)){
                if(!file_exists(storage_path("app/".$ruta_o_archivo))){
                    $arr[] = $file;
                }
            } else {
               // $arr[] = $file;
            }

            //return  $arr ;

        }
        return $arr;
    }
}