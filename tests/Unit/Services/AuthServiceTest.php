<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function test_authenticate_returns_token_for_valid_cpf_cnpj()
    {
        $client = Client::factory()->create(['cpf_cnpj' => '12345678900']);

        $result = $this->authService->authenticate('12345678900');

        $this->assertNotNull($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($client->id, $result['client_id']);
    }

    public function test_authenticate_returns_null_for_invalid_cpf_cnpj()
    {
        $result = $this->authService->authenticate('00000000000');

        $this->assertNull($result);
    }
}
