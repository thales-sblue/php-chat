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
            'email' => 'required|email|unique:clients',
            'type' => 'required|in:pre,pos',
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
            'email' => 'sometimes|required|email|unique:clients,email,' . $id,
            'type' => 'sometimes|required|in:pre,pos',
        ]);

        $client->update($validated);
        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Cliente deletado com sucesso']);
    }
}
