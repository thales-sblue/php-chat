<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'sender_id' => Client::factory(),
            'recipient_id' => Client::factory(),
            'content' => $this->faker->sentence,
            'priority' => 'normal',
            'status' => 'queued',
        ];
    }
}

