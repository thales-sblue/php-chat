<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'name' => 'Admin',
            'cpf_cnpj' => '00000000000',
            'phone' => '44999999999',
            'balance' => 10000.00,
            'limit' => 5000.00,
            'status' => 'active',
        ]);
    }
}
