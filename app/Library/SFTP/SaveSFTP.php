<?php
/**
 * Created by PhpStorm.
 * User: Luis Rodríguez
 * Date: 29/08/2018
 * Time: 12:27 PM
 */

namespace App\Library\SFTP;

use App\Cove;
use App\Expediente;
use App\Library\PedimentoUpload;
use App\Pedimento;
use App\PedimentosAsignado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MrGenis\Library\XmlToArray;

class SaveSFTP
{
    /**
     * Guarda la información del expediente en la BD
     *
     * @param $nombre_expediente
     * @param $empresa_id
     * @return bool|mixed
     */
    public function storeExpediente($nombre_expediente, $empresa_id){
        $existe = DB::table('expedientes')->where('nombre', $nombre_expediente)->first();
        if(empty($existe)){
            $last_id = DB::table('expedientes')->orderBy('id', 'desc')->first();
            $options = [
                'nombre' => $nombre_expediente,
                'expediente' => str_pad($last_id->id+1, 6, 0, STR_PAD_LEFT),
                'descripcion' => 'Expediente generado por el proceso automático',
                'agente_aduanal' => '0',
                'empresa_id' => $empresa_id,
                'status' => 'Abierto'
            ];
            $expediente = Expediente::create($options);
            if($expediente){
                return $expediente->id;
            } else {
                return false;
            }
        } else {
            return $existe->id;
        }
    }

    /**
     * Guarda la información de pedimento en la BD
     *
     * @param $path
     * @param $expediente_name
     * @param $empresa_id
     * @param $archivo
     * @return bool|mixed
     */
    public function storePedimento($path, $expediente_id, $empresa_id, $archivo){
        $pedimento = new PedimentoUpload();
        $pedimento_json = $pedimento->pedimento_json($path);

        $existe = DB::table('pedimentos')->where('pedimento', $pedimento_json['datos_pedimento']['num_pedimento'])->first();
        if(empty($existe)){
            $json_pedimento = json_encode($pedimento_json);
            // Creación de expediente
            $pedim = new Pedimento();
            $pedim->empresa_id     = $empresa_id;
            $pedim->pedimento      = $pedimento_json['datos_pedimento']['num_pedimento'];
            $pedim->aduanaDespacho = $pedimento_json['datos_pedimento']['id_aduana'];
            $pedim->impExpNombre   = $pedimento_json['datos_pedimento']['nombre_imp_exp'];
            $pedim->tipoOperacion  = $pedimento_json['datos_pedimento']['tipoOperacion'];
            $pedim->json           = $json_pedimento;
            $pedim->archivoM       = $archivo;
            $pedim->expediente_id  = $expediente_id;
            if($pedim->save()){
                return $pedim->id;
            } else {
                return false;
            }
        } else {
            return $existe->id;
        }
    }

    /**
     * Asigna el pedimento a un expediente en la BD
     *
     * @param $expediente_id
     * @param $pedimento_id
     */
    public function asignarPedimento($expediente_id, $pedimento_id){
        $existe = DB::table('pedimentos_asignados')->where([
            ['id_pedimento', '=', $pedimento_id],
            ['id_expediente', '=', $expediente_id]
        ])->get();
        if(empty($existe)){
            $options =[
                'id_pedimento' => $pedimento_id,
                'id_expediente' => $expediente_id
            ];
            PedimentosAsignado::create($options);
        }
    }

    /**
     * Guarda el nombre del archivo pdf del pedimento en la BD
     *
     * @param $archivoPDF
     * @param $expediente_id
     * @return bool
     */
    public function storeNamePdfPedimento($archivoPDF, $expediente_id){
        $pedimento = DB::table('pedimentos')->where('expediente_id', $expediente_id);
        if($pedimento->update(['archivoPDF' => $archivoPDF])){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Guarda la información del cove en la BD
     *
     * @param $path
     * @param $expediente_id
     * @param $file_name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCove($path, $expediente_id, $file_name){
        $existe = Cove::where('usr_num_cove', $file_name)->first();
        $empresa_id = Session::get('id');
        if(empty($existe)){
            $datos_cove = $this->getJsonCove($path, $file_name);

            if(!empty($datos_cove)){
                // Save Info
                $cove = new Cove();
                $cove->id_usuario = Session::get('id_usuario');
                $cove->id_empresa = $empresa_id;

                if($expediente_id){
                    $cove->id_expediente = $expediente_id;
                }
                $cove->usr_num_cove = $file_name;
                $cove->xml          = $datos_cove['xml'];
                $cove->json_cove    = $datos_cove['json_cove'];
                $cove->id_fiscal    = '';// $cove_info['emisor']['identificacion'];
                // $cove->pdfs      = $path.$datos_cove['1']['pdf'];

                if($cove->save()) {
                    // Si guarda correctamente el cove, devi
                    return $cove->id;
                } else {
                    return redirect()
                        ->back()
                        ->with('message', 'Error al guardar el cove');
                }
            }
        } else {
            // Busca el archivo pdf para asignarlo al cove en la BD
            return $existe->id;
        }
    }

    public function storeCovePdf($cove_id, $file_name){
        $cove = Cove::where('id',$cove_id);
        if($cove->update(['pdfs' => $file_name])){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Devuelve la información que se guardara del cove (JSON)
     *
     * @param $path
     * @param $file_name
     * @return array
     */
    public function getJsonCove($path, $file_name){
        $xml_cove = file_get_contents($path.$file_name);
        $array_cove = XmlToArray::convert($xml_cove);
        if(!isset($array_cove['comprobantes'])){
            $array_ok['comprobantes'] = $array_cove['Body']['solicitarRecibirCoveServicio']['comprobantes'];
            $array_ok['_root'] = $array_cove['_root'];
            $array_cove = $array_ok;
        }

        $json_cove = collect($array_cove)->tojson();

        $data_cove = array(
            'xml' => $file_name,
            'json_cove' => $json_cove
        );
        return $data_cove;
    }

    // Con el nombre del cove(XML), busca un archivo que contiene un nombre similar para de el
    // obtener el PDF que le corresponde y posteriormente guardar el nombre en la base de datos.
    public function getCovePdf($cove_id, $directory_path, $file_name){
        $path_compare_file = $directory_path.'RespSolCOVE_'.$file_name;
        $compare_file = file_get_contents($path_compare_file);
        $array_compare_file = XmlToArray::convert($compare_file);
        $cove_pdf = $array_compare_file['Body']['solicitarConsultarRespuestaCoveServicioResponse']['respuestasOperaciones']['eDocument'];
        $file_name_cove_pdf = 'ACUSE_DE_VALOR_'.$cove_pdf.'.pdf';
        $bool = $this->storeCovePdf($cove_id, $file_name_cove_pdf);
        return $bool;
    }
}