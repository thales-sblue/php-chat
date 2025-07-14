<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
    public function all(): Collection
    {
        return Client::all();
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function find(int $id): Client
    {
        return Client::findOrFail($id);
    }

    public function update(int $id, array $data): Client
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client;
    }
}
