<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_message_stores_message_and_updates_conversation()
    {
        $sender = Client::factory()->create();
        $recipient = Client::factory()->create();

        $conversation = Conversation::factory()->create([
            'sender_id' => min($sender->id, $recipient->id),
            'recipient_id' => max($sender->id, $recipient->id),
        ]);

        $service = new MessageService();
        $message = $service->send([
            'conversation_id' => $conversation->id,
            'recipient_id' => $recipient->id,
            'content' => 'Olá',
        ], $sender->id);

        $this->assertEquals('Olá', $message->content);
        $this->assertEquals('queued', $message->status);
        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'last_message_content' => 'Olá',
        ]);
    }
}
