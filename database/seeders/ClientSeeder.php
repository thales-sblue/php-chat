<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'name' => 'João Silva',
            'cpf_cnpj' => '12345678901',
            'email' => 'joao@teste.com',
            'type' => 'pre',
            'balance' => 20.00
        ]);

        Client::create([
            'name' => 'Maria Oliveira',
            'cpf_cnpj' => '98765432100',
            'email' => 'maria@teste.com',
            'type' => 'post',
            'limit' => 100.00
        ]);
    }
}
