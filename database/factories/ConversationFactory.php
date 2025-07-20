<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversation>
 */
class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'sender_id' => Client::factory(),
            'recipient_id' => Client::factory(),
            'last_message_content' => $this->faker->sentence,
            'last_message_time' => now(),
            'unread_count' => 0,
        ];
    }
}

