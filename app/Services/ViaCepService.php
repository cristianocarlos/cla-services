<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    public function fetch(string $cep) {
        $configUrl = config('services.via_cep.url');
        if (empty($configUrl)) {
            throw new \Exception('services.via_cep.url não configurado');
        }
        $response = Http::get("{$configUrl}/{$cep}/json/");
        if (!$response->successful()) {
            throw new \Exception('Ocorreu um erro no serviço do fornecedor Via CEP . Resposta inválida (' . $response ?: '[vazio]' . ')');
        }
        return $response->json();
    }
}
