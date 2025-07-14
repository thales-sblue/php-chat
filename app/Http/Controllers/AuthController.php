<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'required',
        ]);

        $authData = $this->authService->authenticate($request->cpf_cnpj);

        if (!$authData) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        return response()->json($authData);
    }
}
