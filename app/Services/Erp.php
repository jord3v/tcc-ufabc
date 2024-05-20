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
        return $response->ok() ? str_replace('"', '', $response->getBody()->getContents()) : abort($response->status());
    }
}