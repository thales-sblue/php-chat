<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\ChatService;
use App\Services\MessageService;
use App\Services\ConversationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_messages_marks_all_as_read()
    {
        $user = Client::factory()->create();
        $other = Client::factory()->create();

        $conversation = Conversation::factory()->create([
            'sender_id' => $other->id,
            'recipient_id' => $user->id,
        ]);

        Message::factory()->count(3)->create([
            'conversation_id' => $conversation->id,
            'recipient_id' => $user->id,
            'status' => 'received',
        ]);

        $chatService = new ChatService(
            new ConversationService(),
            new MessageService()
        );

        $chatService->getMessages($conversation->id, $user->id);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversation->id,
            'recipient_id' => $user->id,
            'status' => 'received',
        ]);
    }
}
