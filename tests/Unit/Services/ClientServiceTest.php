<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ClientService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ClientService();
    }

    public function test_all_returns_clients()
    {
        Client::factory()->count(3)->create();

        $clients = $this->service->all();

        $this->assertCount(3, $clients);
    }

    public function test_create_stores_client()
    {
        $data = [
            'name' => 'João Silva',
            'cpf_cnpj' => '12345678900',
            'email' => 'joao@email.com',
        ];

        $client = $this->service->create($data);

        $this->assertDatabaseHas('clients', ['cpf_cnpj' => '12345678900']);
        $this->assertEquals('João Silva', $client->name);
    }

    public function test_find_returns_client_by_id()
    {
        $client = Client::factory()->create();

        $found = $this->service->find($client->id);

        $this->assertEquals($client->id, $found->id);
    }

    public function test_update_modifies_client()
    {
        $client = Client::factory()->create([
            'name' => 'Antigo Nome',
        ]);

        $updated = $this->service->update($client->id, ['name' => 'Novo Nome']);

        $this->assertEquals('Novo Nome', $updated->name);
        $this->assertDatabaseHas('clients', ['name' => 'Novo Nome']);
    }
}
