<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'cpf_cnpj' => $this->faker->unique()->numerify('###########'),
            'phone' => $this->faker->phoneNumber,
            'balance' => 0,
            'limit' => 0,
            'status' => 'active',
        ];
    }
}
