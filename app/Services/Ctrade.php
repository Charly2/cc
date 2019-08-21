<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Repositories\EloquentPedimentoRepository;

class Ctrade
{
    protected $client;
    protected $pedimento;
    protected $baseUrl = 'http://internal-cpabase-2031371593.us-east-1.elb.amazonaws.com:8081/ctrade/';
    protected $defaults;

    public function __construct(EloquentPedimentoRepository $pedimento, Client $client)
    {
        $this->pedimento = $pedimento;
        $this->client = $client;
        $this->defaults = [
            'uid' => 43275814,
            'usuario' => 'demo',
            'pagina' => 1,
            'tamano' => 100,
        ];
    }

    /**
     * @return object
     */
    public function actualizaPedimentos($rfc, $ejercicio, $periodo, $empresa)
    {
        $response = $this->client->get($this->getUrlPedimentos([
            '<%rfc%>' => $rfc,
            '<%ejercicio%>' => $ejercicio,
            '<%periodo%>' => $periodo,
        ]))->getBody();

        $pedimentos = json_decode($response);

        return $this->save($pedimentos, $empresa);
    }

    /**
     * @return object
     */
    public function pedimento(array $data)
    {
        $response = $this->client->get($this->getUrlPedimento([
            '<%rfc%>' => $data['rfc'],
            '<%pedimento%>' => $data['pedimento'],
        ]))->getBody();

        $pedimento = json_decode($response);

        return $pedimento;
    }

    /**
     * @return string
     */
    private function getUrlPedimentos(array $data)
    {
        $url = $this->baseUrl."ver/archivo_m?&uid={$this->defaults['uid']}&empresa=<%rfc%>&usuario={$this->defaults['usuario']}&ejercicio=<%ejercicio%>&periodo=<%periodo%>&pagina={$this->defaults['pagina']}&tamanoPagina={$this->defaults['tamano']}&todo=true";
        $url = $this->parseUrl($data, $url);

        return $url;
    }

    /**
     * @return string
     */
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

    /**
     * @return bool
     */
    public function save($data, $empresa)
    {
        foreach ($data->doc as $pedimento) {
            $this->pedimento->updateOrCreate([
                'pedimento' => isset($pedimento->pedimento) ? $pedimento->pedimento : '',
                'aduanaDespacho' => isset($pedimento->aduanaDespacho) ? $pedimento->aduanaDespacho : '',
                'fechaPago' => isset($pedimento->fechaPago) ? $pedimento->fechaPago : '',
                'fechaPedimento' => isset($pedimento->fechaPedimento) ? $pedimento->fechaPedimento : '',
                'impExpNombre' => isset($pedimento->importadorExportadorNombre) ? $pedimento->importadorExportadorNombre : '',
                'impuestos' => isset($pedimento->impuestos) ? $pedimento->impuestos : '',
                'tipoOperacion' => isset($pedimento->tipoOperacion) ? $pedimento->tipoOperacion : '',
                'valorAduana' => isset($pedimento->valorMercanciaAduana) ? $pedimento->valorMercanciaAduana : '',
                'rfc' => $empresa->rfc,
                'empresa_id' => $empresa->id,
            ]);
        }
    }
}
