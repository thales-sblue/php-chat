<?php

namespace App\Services;

use App\Models\Client;

class AuthService
{
    public function authenticate(string $cpfCnpj): ?array
    {
        $client = Client::where('cpf_cnpj', $cpfCnpj)->first();

        if (!$client) {
            return null;
        }

        $token = $client->createToken('api-token')->plainTextToken;

        return [
            'token' => $token,
            'client_id' => $client->id,
        ];
    }
}
