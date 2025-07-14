<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index()
    {
        return response()->json($this->clientService->all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'cpf_cnpj' => 'required|string|unique:clients',
            'phone' => 'required|string',
            'balance' => 'nullable|numeric',
            'limit' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $client = $this->clientService->create($validated);
        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = $this->clientService->find($id);
        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'cpf_cnpj' => 'sometimes|required|string|unique:clients,cpf_cnpj,' . $id,
            'phone' => 'sometimes|required|string',
            'balance' => 'nullable|numeric',
            'limit' => 'nullable|numeric',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $client = $this->clientService->update($id, $validated);
        return response()->json($client);
    }
}
