<?php
namespace App\Library\SSH;

use App\Library\SFTP\SaveSFTP;
use Collective\Remote\RemoteFacade as SSH;

class SshServer
{
    protected $carpetas;

    public function isFolder($ruta_server, $ruta_o_archivo, $ruta_local, $expediente_name){
        $folder = $ruta_server.'/'.$ruta_o_archivo;

        // Verifica si la ruta es un directorio o un archivo
        $expreg_carpeta = '/^\w+[\w\-]+$/';
        $expreg_archivo = '/^\w+[\.\w\-]+$/';
        if(preg_match($expreg_carpeta, $ruta_o_archivo)){
            // Metodo para ir al servidor y extraer una lista con los nombres de los
            // archivos encontrados
            $commands = [
                'cd '.$folder,
                'ls'
            ];
            $this->carpetas = '';
            SSH::into('runtime')->run($commands, function($line){
                $this->carpetas = $line;
            });

            // Separa la lista por espacios para encontrar los archivos
            $lista = preg_split('/\s/', $this->carpetas);
            // Ordena los archivos
            natcasesort($lista);
            // Recorre la lista de nuevo para encontrar un archivo o directorio
            foreach ($lista as $ruta_o_archivo2){
                $resulado = $this->isFolder($folder, $ruta_o_archivo2, $ruta_local, $expediente_name);
                if(!empty($resulado)){
                    $result_files[] = $resulado;
                }
            }
        } elseif(preg_match($expreg_archivo, $ruta_o_archivo)) {
            // En caso de encontrar un archivo hace lo siguiente
            $archivo = $folder;
            try {
                // Expresiones regulares para pedimentos(m) y PDF de Pedimentos
                // m1234657.123
                $expreg_m = '/^(m|M)(\d{7})\.(\d{3})$/';
                // v1234-123456.pdf
                $expreg_m_pdf = '/^(v|V)(\d{4})-(\d{6}|\d{7})\.(pdf|PDF)$/';
                // 123456789012345_p.pdf
                $expreg_m2_pdf = '/^\d{15}_p\.(pdf|PDF)$/';
                // 17-1600057_C600556351.xml
                $expreg_cove = '/^\d{2}-\d{7}_C\d{9}\.(xml|XML)$/';
                // ACUSE_DE_VALOR_COVE17285EHM7.pdf
                $expreg_cove_pdf = '/^ACUSE_DE_VALOR_COVE((\d|\w){9})\.(pdf|PDF)$/';
                // RespSolCOVE_17-1600057_C600556351.xml
                $expreg_cove_match = '/^RespSolCOVE_\d{2}-\d{7}_C\d{9}\.(xml|XML)$/';

                // Si el elemento coincide con los archivos
                $save = new SaveSFTP();
                if(preg_match($expreg_m, $ruta_o_archivo)) {
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    $archivo_local = $ruta_local.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo;
                    if(!file_exists($archivo_local)){
                        SSH::into('runtime')->get($archivo, $archivo_local);
                        $storage = $expediente_id.'/pedimentos/'.$ruta_o_archivo;
                        $result_files = $this->respuesta('m', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                }
                elseif(preg_match($expreg_m_pdf, $ruta_o_archivo) || preg_match($expreg_m2_pdf, $ruta_o_archivo)) {
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    $archivo_local = $ruta_local.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo;
                    if(!file_exists($archivo_local)){
                        SSH::into('runtime')->get($archivo, $archivo_local);
                        $storage = $expediente_id.'/pedimentos/'.$ruta_o_archivo;
                        $result_files = $this->respuesta('m_pdf', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_cove, $ruta_o_archivo)){
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    $archivo_local = $ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo;
                    if(!file_exists($archivo_local)){
                        SSH::into('runtime')->get($archivo, $archivo_local);
                        $storage = $ruta_local.'/'.$expediente_id.'/coves/';
                        $result_files = $this->respuesta('cove', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_cove_pdf, $ruta_o_archivo)){
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    $archivo_local = $ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo;
                    if(!file_exists($archivo_local)){
                        SSH::into('runtime')->get($archivo, $archivo_local);
                        $storage = $ruta_local.'/'.$expediente_id.'/coves/';
                        $result_files = $this->respuesta('cove_pdf', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_cove_match, $ruta_o_archivo)){
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    $archivo_local = $ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo;
                    if(!file_exists($archivo_local)){
                        SSH::into('runtime')->get($archivo, $archivo_local);
                        $storage = $ruta_local.'/'.$expediente_id.'/coves/';
                        $result_files = $this->respuesta('cove_match', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } else {
                    $result_files = [];
                }
            } catch(\Exception $e){
                $result_files = $this->respuesta('-', $archivo, 500, $expediente_id . $e->getMessage(), '-', $ruta_o_archivo);
            }
        }
        if(isset($result_files)){
            return $result_files;
        } else {
            return $result_files = [];
        }
    }

    public function respuesta($type_file, $file, $status, $expediente_id, $storage, $file_name){
        // Devuelve el resultado de cuando se guarda un archivo
        return array(
            'timestamp'   => date('Y-m-d H:i:s'),
            'status'      => $status,
            'expediente_id' => $expediente_id,
            'type_file'   => $type_file ,
            'metadata'    => $file,
            'storage'     => $storage,
            'file_name'   => $file_name
        );
    }

    public function saveExpediente($ruta_local, $expediente_name){
        $empresa_id = session()->get('id');
        // Metodos para guardar los archivos
        $saveSFTP = new SaveSFTP();
        // Obtiene el id del expediente, sino existe lo crea y trae el id
        $expediente_id = $saveSFTP->storeExpediente($expediente_name, $empresa_id);

        // Si no existe la carpeta del expediente, la crea
        if (!file_exists($ruta_local.'/'.$expediente_id)){
            mkdir($ruta_local.'/'.$expediente_id);
            mkdir($ruta_local.'/'.$expediente_id.'/coves');
            mkdir($ruta_local.'/'.$expediente_id.'/pedimentos');
        }
        return $expediente_id;
    }
}