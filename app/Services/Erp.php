<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Erp
{
    protected $baseUrl, $apiKey, $client;

    public function __construct()
    {
        $this->baseUrl = config('services.erp.url');
        $this->apiKey = config('services.erp.key');
        $this->client = Http::withHeaders([
            'Chave' => $this->apiKey,
        ])->baseUrl($this->baseUrl);
    }

    public function post($endpoint, $data)
    {
        $response = $this->client->post($endpoint, $data);
        $process =  str_replace('"', '', $response->getBody()->getContents());
        if($endpoint === 'IncluirProtocolo')
            $this->processing($process);
        return $response->ok() ? $process : abort($response->status());
    }

    public function processing($process){
        $data = [
            "tipoProtocolo" => "ADM",
            "numeroProtocolo" => $process,
            "localizacao" => 13,
            "destinatario" => "",
            "remetente" => auth()->user()->username,
            "situacao"  => 6,
            "dataFinal"  => "",
            "observacao"  => "teste obs ANDAMENTO",
            "complemento"  => "TESTE COMPLEMENTO",
        ];

        $this->client->post('tramitarProtocolo', $data);
    }
}