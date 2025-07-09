<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'required',
        ]);

        $client = Client::where('cpf_cnpj', $request->cpf_cnpj)->first();

        if (!$client) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        $token = $client->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
