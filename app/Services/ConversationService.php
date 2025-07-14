<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Collection;

class ConversationService
{
    public function startConversation(string $cpfCnpj, int $senderId): ?array
    {
        $recipient = Client::where('cpf_cnpj', $cpfCnpj)->first();
        if (!$recipient) {
            return null;
        }

        $sender = Client::find($senderId);
        if (!$sender) {
            return null;
        }

        $senderIdOrdered = min($sender->id, $recipient->id);
        $recipientIdOrdered = max($sender->id, $recipient->id);

        $conversation = Conversation::firstOrCreate(
            [
                'sender_id' => $senderIdOrdered,
                'recipient_id' => $recipientIdOrdered,
            ],
            [
                'last_message_content' => '',
                'last_message_time' => now(),
                'unread_count' => 0,
            ]
        );

        return [
            'conversation_id' => $conversation->id,
            'recipient_id' => $recipientIdOrdered,
            'recipient_name' => $recipient->name,
        ];
    }

    public function getConversations(int $userId): Collection
    {
        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->with(['sender', 'recipient'])
            ->orderByDesc('last_message_time')
            ->get();

        return $conversations->map(function ($conversation) use ($userId) {
            $otherId = $conversation->sender_id === $userId
                ? $conversation->recipient_id
                : $conversation->sender_id;

            $other = Client::find($otherId);

            return [
                'id' => $conversation->id,
                'sender' => $conversation->sender_id,
                'recipient' => $other,
                'last_message' => $conversation->last_message_content,
                'last_message_time' => $conversation->last_message_time,
                'unread_count' => $conversation->unread_count,
            ];
        });
    }

    public function getMessages(int $conversationId, int $userId): ?array
    {
        $conversation = Conversation::with(['sender', 'recipient'])
            ->where('id', $conversationId)
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('recipient_id', $userId);
            })->first();

        if (!$conversation) {
            return null;
        }

        $messages = Message::where('conversation_id', $conversationId)
            ->orderBy('created_at')
            ->get();

        $recipient = $conversation->sender_id === $userId
            ? $conversation->recipient
            : $conversation->sender;

        return [
            'messages' => $messages,
            'recipient' => [
                'id' => $recipient->id,
                'name' => $recipient->name,
            ],
        ];
    }
}
