<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $token = Str::uuid();
        $client->api_token = $token;
        $client->save();

        return response()->json(['token' => $token]);
    }
}
