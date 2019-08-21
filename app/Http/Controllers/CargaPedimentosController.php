<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use GuzzleHttp\Client;
use App\Pedimento;
class CargaPedimentosController extends Controller
{
    //
    protected $client;

    protected $pedimentos;

    protected $baseUrl = 'http://internal-cpaBase-2031371593.us-east-1.elb.amazonaws.com:8081/ctrade/';

    protected $numPedimentos;

    protected $defaults;
    /**
     * CargaPedimentosController constructor.
     * @param $client
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->defaults = [
            'uid' => 43275814,
            'usuario' => 'demo',
            'pagina' => 1,
            'tamano' => 100,
        ];
    }

    public function insertaPedimentos()
    {
    	$pedimentos = json_decode($this->pedimento());
    	foreach ($pedimentos as $pedimento) {
    		$pedimento2 = json_decode($pedimento);
    		Pedimento::create([
                'pedimento'      => $pedimento2->doc->pedimento->generales->pedimento,
                'aduanaDespacho' => $pedimento2->doc->pedimento->generales->aduanaDespacho,
                'fechaPago'      => $pedimento2->doc->pedimento->generales->fechas['1']->fecha,
                'fechaPedimento' => $pedimento2->doc->pedimento->generales->fechas['0']->fecha,
                'impExpNombre'   => utf8_encode($pedimento2->doc->pedimento->generales->importadorExportadorNombre),
                'tipoOperacion'  => $pedimento2->doc->pedimento->generales->tipoOperacion,
                'empresa_id'     => '2',
                'json'           => $pedimento
    			]);
    	}
    	return "Se inserto corretamente los pedimentos";

    }

     public function pedimento()
    {
        $numPedimentos = $this->extraeNumeroPedimento();
        foreach ($numPedimentos as $pedimento) {
        	$client = new Client();
        	$response[] = $client->get($this->getUrlPedimento([
            '<%rfc%>' => 'DME9204099R6',
            '<%pedimento%>' => $pedimento,
        	]))->getBody()->getContents();
        }
        return json_encode($response);
    }

    public function extraeNumeroPedimento()
    {
    	$pedimentos = $this->actualizaPedimentos();
    	foreach ($pedimentos->doc as $pedimento) {
    		$numPedimento[] = $pedimento->pedimento;
    	}
    	return $numPedimento;
    }

    public function actualizaPedimentos()
    {
        $response = $this->client->get($this->getUrlPedimentos([
            '<%rfc%>' => 'DME9204099R6',
            '<%ejercicio%>' => '2016',
            '<%periodo%>' => '12',
        ]))->getBody();

        $pedimentos = json_decode($response);
        return $pedimentos;
    }

    
    /**
     * @return string
     */
    private function getUrlPedimentos(array $data)
    {
        $url = $this->baseUrl."ver/archivo_m?&uid={$this->defaults['uid']}&empresa=<%rfc%>&usuario={$this->defaults['usuario']}&ejercicio=<%ejercicio%>&periodo=<%periodo%>&pagina={$this->defaults['pagina']}&tamanoPagina={$this->defaults['tamano']}";
        $url = $this->parseUrl($data, $url);
        return $url;
    }

    private function getUrlPedimento(array $data)
    {
        $url = $this->baseUrl."ver/pedimento/<%pedimento%>?&uid={$this->defaults['uid']}&empresa=<%rfc%>&usuario={$this->defaults['usuario']}";
        $url = $this->parseUrl($data, $url);

        return $url;
    }

    /**
     * @return string
     */
    private function parseUrl(array $data, $url)
    {
        foreach ($data as $key => $value) {
            $url = str_replace($key, $value, $url);
        }

        return $url;
    }

}
