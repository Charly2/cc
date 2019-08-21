<?php
namespace App\Library;
use App\Pedimento;

class PedimentoUpload
{


    public function upload_pedimento($fileName, $path,$id_empresa)
    {
        //extraigo la primera letra par avalidar que es un archivo M
        $fileInitial = substr($fileName , 0, 1);
        $fileInitial = strtoupper($fileInitial);

        if ($fileInitial == 'M'){
            //$archivo = $path.$fileName;
            $pedimento_json= $this->pedimento_json($path);

            $pedim = new Pedimento();
            $pedim->empresa_id     = $id_empresa;
            $pedim->pedimento      = $pedimento_json['datos_pedimento']['num_pedimento'];
            $pedim->aduanaDespacho = $pedimento_json['datos_pedimento']['id_aduana'];
            $pedim->fechaPago      = $this->fecha_pedimento($pedimento_json['fechas']['fecha_pago']);

            $pedim->fechaPedimento = $this->fecha_pedimento($pedimento_json['fechas']['fecha_entrada']);
            $pedim->id_fiscal      =$pedimento_json['datos_cove']['id_fiscal'];

            $pedim->impExpNombre   = $pedimento_json['datos_pedimento']['nombre_imp_exp'];
            $pedim->tipoOperacion  = $pedimento_json['datos_pedimento']['tipoOperacion'];

            $json_pedimento= json_encode($pedimento_json);
            $pedim->json  = $json_pedimento;
            if($pedim->save())
            {
                return 'Se cargo el pedimento exitosamente';
            }else{
                //\File::delete($path."/".$fileName);
                return 'Error en la carga.';
            }
        }else {
            echo "ups";
        }
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
        $archivo  = fopen($path, "r");
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
            $columna=collect(explode('|',$value));
            $datos_cove = array(
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
        }

        //extraigo el num_pedimento para realizar la busqueda en los demas valores
        //$num_pedimento = $datos_pedimento['datos_cove']['num_pedimento'];

        $datoPedimento = $collection->only('501');
        foreach ($datoPedimento[501] as $key => $value) {
            $columna=collect(explode('|',$value));
            $dato_pedimento = array(
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

        }
        return ($datos_pedimento);
    }
}
