<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class AuthClient
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token não informado.'], 401);
        }

        $client = Client::where('api_token', $token)->first();

        if (!$client) {
            return response()->json(['error' => 'Token inválido.'], 401);
        }

        $request->merge(['authenticated_client' => $client]);

        return $next($request);
    }
}
