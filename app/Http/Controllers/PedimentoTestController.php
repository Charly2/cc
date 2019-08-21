<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PedimentoTestController extends Controller
{
    public function indexx()
    {
    	//M1714758.311-m3061442.136-M3633496.006-m3921610.214
    	$path= url('m3921610.214');

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

        $datos = collect($array);
		return $datos->only(501,'505','551',506)->toArray();

        $json_array = json_encode($array);

        $json_array = json_decode($json_array, true);

//->contains('501');
        /* INICIO-CUADRO DE LIQUIDACION */ 
        $datos_pedimento['cuadro_liquidacion'] = array();

        $dta =  (explode( '|', $json_array['510']['0'] ) )['4'];
        array_push( $datos_pedimento['cuadro_liquidacion'] , array('dta'=>$dta));
        $prv =  (explode( '|', $json_array['510']['1'] ) )['4'];
        array_push( $datos_pedimento['cuadro_liquidacion'] , array('prv'=>$prv));
        /*$cnt =  (explode( '|', $json_array['510']['2'] ) )['4'];
        COMENTANDO ESTAS LINEAS YA PASA EL PEDIMENTO HAY QUE CONSIDERAR SI ES EXPORTACIÓN O IMPORTACION
        array_push( $datos_pedimento['cuadro_liquidacion'] , array('cnt'=>$cnt));
        $fila_iva =  (explode( '|', $json_array['557']['0'] ) )['6'];
        array_push( $datos_pedimento['cuadro_liquidacion'] , array('iva'=>$fila_iva));
*/
        //$total=array ($dta,$prv,$cnt,$fila_iva);             
        $total=array ($dta,$prv);             
        array_push( $datos_pedimento['cuadro_liquidacion'] , array('total'=>array_sum($total)));

        /* FIN-CUADRO DE LIQUIDACION*/

         foreach ($json_array['501'] as $row) {
          $columna          =  ( explode( '|', $row ) );
          $datos_pedim = array(
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
        $datos_pedimento['datos_pedimento'] =  $datos_pedim;

        }

        $fila_fechas = array( 
                        'fecha_entrada' => (explode( '|', $json_array['506']['0'] ) )['3'],
                        'fecha_pago'    => (explode( '|', $json_array['506']['1'] ) )['3']
                        );
        $datos_pedimento['fechas'] =  $fila_fechas;


        $valor_cove = array( 
                            'valor_aduana'  => (explode( '|', $json_array['551']['0'] ) )['6'],
                            'valor_dolares' => (explode( '|', $json_array['551']['0'] ) )['9']
                        );
       
        $datos_pedimento['valor_cove'] =  $valor_cove;

          $data_cove=$json_array['505']['0'];
          $columna          =  ( explode( '|', $data_cove ) );
          $datos_cove = array(
             'fecha_cove'          => $columna[2],
             'cove'                => $columna[3],
             'incoterm'            => $columna[4],
             'moneda_fact'         => $columna[5],
             'valor_cove'          => $columna[6],
             'pais'                => $columna[8],
             'id_fiscal'           => $columna[10],
             'nombre_proveedor'    => $columna[11],
             'direccion_proveedor' => $columna[12].' '. $columna[13].', '. $columna[14].', '. $columna[15].' '. $columna[16],
         );

        $datos_pedimento['datos_cove'] =  $datos_cove;



        //dd($datos_pedimento);

        return ($datos_pedimento);

        //$json_pedimento= json_encode($datos_pedimento);
        //$json_pedimento = json_decode($json_pedimento, true);
        //dd($json_pedimento);

    }

    public function index(){

    	
    }
}
