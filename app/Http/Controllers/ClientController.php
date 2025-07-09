<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
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

        $client = Client::create($validated);
        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'cpf_cnpj' => 'sometimes|required|string|unique:clients,cpf_cnpj,' . $id,
            'phone' => 'sometimes|required|string',
            'balance' => 'nullable|numeric',
            'limit' => 'nullable|numeric',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $client->update($validated);
        return response()->json($client);
    }
}
