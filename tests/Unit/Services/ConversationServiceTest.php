<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Services\ConversationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_conversation_creates_conversation()
    {
        $sender = Client::factory()->create();
        $recipient = Client::factory()->create(['cpf_cnpj' => '12345678900']);

        $service = new ConversationService();
        $result = $service->startConversation('12345678900', $sender->id);

        $this->assertNotNull($result);
        $this->assertEquals($recipient->id, $result['recipient_id']);
    }
}
