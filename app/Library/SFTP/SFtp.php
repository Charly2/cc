<?php

namespace App\Library\SFTP;

class SFtp
{
    const PTY_VT102 = 'vt102';
    const PTY_XTERM = 'xterm';

    private $host;
    private $port;

    private $_connection;
    private $_sftp;

    private $exec_pty = SFtp::PTY_XTERM;
    private $exec_env = null;
    private $exec_width = 80;
    private $exec_height = 60;
    private $exec_width_height_type = 0;

    public function __construct($host, $port = 22)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * (PECL ssh2 >= 0.9.0)<br/>
     * Execute a command on a remote server
     *
     * @param $command
     *
     * @return array a stream on success or <b>FALSE</b> on failure.
     */
    public function exec_blocking($command)
    {
        $stream = $this->exec($command);
        if (!$stream) return false;

        $stream_err = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        sleep(1);

        stream_set_blocking($stream_err, true);
        stream_set_blocking($stream, true);

        $content = stream_get_contents($stream);
        $errors = stream_get_contents($stream_err);

        return [
            "stdout" => $content,
            "stderr" => $errors
        ];
    }

    /**
     * (PECL ssh2 >= 0.9.0)<br/>
     * Execute a command on a remote server
     *
     * @param $command
     *
     * @return resource a stream on success or <b>FALSE</b> on failure.
     */
    public function exec($command)
    {
        return @ssh2_exec($this->_connection, $command, $this->exec_pty, $this->exec_env, $this->exec_width,
            $this->exec_height, $this->exec_width_height_type);
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function connect($username, $password)
    {
        $this->_connection = @ssh2_connect($this->host, $this->port);
        $this->login($username, $password);
    }

    /**
     * @return mixed connection of ssh2-sftp
     */
    public function cnn()
    {
        return $this->_connection;
    }

    public function close(){
        $this->exec('exit');
    }

    /**
     * @return mixed instance of sftp
     */
    public function sftp()
    {
        if (!is_resource($this->_sftp)) $this->_sftp = @ssh2_sftp($this->_connection);
        return $this->_sftp;
    }

    /**
     * @param string $dirname   Path of the new directory.
     * @param int    $mode      Permissions on the new directory
     * @param bool   $recursive If recursive is <b>TRUE</b> any parent directories required for <b>dirname</b> will be
     *                          automatically created as well
     *
     * @return bool
     */
    public function mkdir($dirname, $mode = 0777, $recursive = false)
    {
        return ssh2_sftp_mkdir($this->sftp(), $dirname, $mode, $recursive);
    }


    /**
     * List files and directories inside the specified absolute path
     *
     * @param string $dirname       The directory that will be scanned.
     * @param int    $sorting_order By default, the sorted order is alphabetical in ascending order. If the optional
     *                              <b>sorting_order</b> is set to <b>SCANDIR_SORT_DESCENDING</b>, then the sort order
     *                              is alphabetical in descending order. If it is set to <b>SCANDIR_SORT_NONE</b> then
     *                              the result is unsorted.
     *
     * @return array
     */
    public function scandir($dirname, $sorting_order = SCANDIR_SORT_ASCENDING)
    {
        $uri = $this->uri($dirname);
        $remote_files = scandir($uri, $sorting_order);
        $files = [];
        foreach ($remote_files as $file) {
            if ($file != '.' && $file != '..') $files[] = $file;
        }
        unset($uri, $remote_files, $file);
        return $files;
    }

    /**
     * Copy a file from the local filesystem to the remote server using the SCP protocol
     *
     * @param string $local_file  Path to the local file
     * @param string $remote_file Path to the remote file
     * @param int    $create_mode The file will be created with the mode specified by <u>create_mode</u>.
     *
     * @throws Exception local file no exist!
     * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function scp_send($local_file, $remote_file, $create_mode = 0644)
    {
        if (!file_exists($local_file)) {
            throw  new Exception("Local file ({$local_file}) no exist!");
        }

        $stream = @fopen($this->uri($remote_file), 'w');
    
        try {

            if (!$stream) {
                // no se logro enviar el archivo mediante flujo de datos.
                // se intenta enviar la informacion por medio de SCP
                // NOTA! Utilizar SCP aumenta ligeramente la carga de trabajo.
                if (!@ssh2_scp_send($this->_connection, $local_file, $remote_file, $create_mode)) {
                    throw new Exception("Unable to send local file to host {$this->host}. " . PHP_EOL .
                        "File:({$local_file})", Exception::ERROR_SEND_DATA);
                }
                return true;
            }

            $data2send = @file_get_contents($local_file);
            if (!$data2send) {
                throw new Exception("Could not open local file ({$local_file})",
                    Exception::ERROR_SEND_OPENLOCAL);
            }

            if (@fwrite($stream, $data2send) == false) {
                throw new Exception("Could not send data from file ({$local_file}) to host {$this->host}");
            }

        } catch (Exception $e) {
            throw $e;
        } finally {
            if (is_resource($stream)) @fclose($stream);
        }
        return true;
       
    }

    /**
     * Copy a file from the remote server to the local filesystem using the SCP protocol.
     *
     * @param string $remote_file Path to the remote file
     * @param string $local_file  Path to the local file
     *
     * @return bool Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @throws Exception when is make impossible to copy the remote file.
     */
    public function scp_revc($remote_file, $local_file)
    {
        $stream = @fopen($this->uri($remote_file), 'r');

        try {
            if (!$stream) {
                // no logo inciar la conexion para el envio mediante flujo de datos.
                // se intenta obtener la informacion por medio de SCP.
                // NOTA! Utilizar SCP aumenta ligeramente la carga de trabajo.
                if (!@ssh2_scp_recv($this->_connection, $remote_file, $local_file)) {
                    throw new Exception("Unable to receive remote file {$this->host}. " . PHP_EOL .
                        "File:({$remote_file})", Exception::ERROR_REV_DATA);
                }
                return true;
            }
            $contents = stream_get_contents($stream);
            if (!$contents) {
                throw new Exception("Could no retrive stream data from host {$this->host} for file ({$remote_file})",
                    Exception::ERROR_REVC_OPENREMOTE);
            }
            file_put_contents($local_file, $contents);
        } catch (Exception $e) {
            throw new Exception("Unable to receive remote file {$this->host}. " . PHP_EOL .
                "File:({$remote_file})", Exception::ERROR_REV_DATA);
        } finally {
            if (is_resource($stream)) @fclose($stream);
        }

        return true;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @throws Exception Auth Fail, SFTP not initialize
     */
    private function login($username, $password)
    {
        $result = @ssh2_auth_password($this->_connection, $username, $password);
        if (!$result) {
            throw new Exception("HOST AUTH FAIL!\nPosible Bad Credentials");
        }

        /*$this->_sftp = ssh2_sftp($this->_connection);
        if (!$this->_sftp) {
            echo "SFTP CUOLD NOT INITIALIZE!";
        }*/
    }

    public function uri($dirname)
    {
        /*$sftp = ssh2_sftp($this->_connection);*/
        $uri = 'ssh2.sftp://' . intval($this->sftp()) . $dirname;
        return $uri;
    }

    /**
     * Función que verifica si un elemento es un directorio o un archivo
     *
     * @param $ruta
     * @param $ruta_o_archivo
     * @param $ruta_local
     * @param $expediente_name
     * @return array
     */
    public function isFolder($ruta, $ruta_o_archivo, $ruta_local, $expediente_name){

        $folder = $ruta.'/'.$ruta_o_archivo;

        // Verifica si la ruta es un directorio
        if(is_dir($this->uri($folder))){
            // Obtiene una lista del contenido de la carpeta
            $lista = $this->scandir($folder);
            // Ordena la lista independientemente si es mayuscula o minuscula
            natcasesort($lista);
            // Va a recorrer la lista de nuevo buscando si los elementos son directorios
            foreach ($lista as $ruta_o_archivo2){
                $resultado= $this->isFolder($folder, $ruta_o_archivo2, $ruta_local, $expediente_name);
                // Si encuentra un resultado lo mete en el arreglo de $result_files
                if(!empty($resultado)){
                    $result_files[] =  $resultado;
                }
            }
        } else {
            $archivo = $folder;
            try {
                // Expresiones regulares para pedimentos(m) y PDF de Pedimentos
                // m1234657.123
                $expreg_m = '/^(m|M)(\d{7})\.(\d{3})$/';
                // p1234-123456.pdf
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
                if(preg_match($expreg_m, $ruta_o_archivo)) {
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    if(!file_exists($ruta_local.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo)){
                        $this->scp_revc($archivo, $ruta_local.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo);
                        $storage = $expediente_id.'/pedimentos/'.$ruta_o_archivo;
                        $result_files = $this->respuesta('m', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_m_pdf, $ruta_o_archivo) || preg_match($expreg_m2_pdf, $ruta_o_archivo)) {
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    if(!file_exists($ruta_local.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo)){
                        $this->scp_revc($archivo, $ruta_local.'/'.$expediente_id.'/pedimentos/'.$ruta_o_archivo);
                        $storage = $expediente_id.'/pedimentos/'.$ruta_o_archivo;
                        $result_files = $this->respuesta('m_pdf', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_cove, $ruta_o_archivo)){
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    if(!file_exists($ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo)){
                        $this->scp_revc($archivo, $ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo);
                        $storage = $ruta_local.'/'.$expediente_id.'/coves/';
                        $result_files = $this->respuesta('cove', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_cove_pdf, $ruta_o_archivo)){
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    if(!file_exists($ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo)){
                        $this->scp_revc($archivo, $ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo);
                        $storage = $ruta_local.'/'.$expediente_id.'/coves/';
                        $result_files = $this->respuesta('cove_pdf', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } elseif(preg_match($expreg_cove_match, $ruta_o_archivo)){
                    $expediente_id = $this->saveExpediente($ruta_local, $expediente_name);
                    if(!file_exists($ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo)){
                        $this->scp_revc($archivo, $ruta_local.'/'.$expediente_id.'/coves/'.$ruta_o_archivo);
                        $storage = $ruta_local.'/'.$expediente_id.'/coves/';
                        $result_files = $this->respuesta('cove_match', $archivo, 200, $expediente_id, $storage, $ruta_o_archivo);
                    } else {
                        return $result_files = [];
                    }
                } else {
                    $result_files = [];
                }
            } catch (\Exception $e){
                // Reporte de errores
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