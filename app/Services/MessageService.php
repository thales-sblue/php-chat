<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    public function send(array $data, int $senderId): Message
    {
        return DB::transaction(function () use ($data, $senderId) {
            $message = Message::create([
                'conversation_id' => $data['conversation_id'],
                'sender_id' => $senderId,
                'recipient_id' => $data['recipient_id'],
                'content' => $data['content'],
                'priority' => $data['priority'] ?? 'normal',
                'status' => 'queued',
            ]);

            $conversation = Conversation::find($data['conversation_id']);
            if ($conversation) {
                $conversation->last_message_content = $message->content;
                $conversation->last_message_time = now();

                if ($conversation->recipient_id === $data['recipient_id']) {
                    $conversation->unread_count += 1;
                }

                $conversation->save();
            }

            return $message;
        });
    }

    public function listByConversation(int $conversationId): Collection
    {
        return Message::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function markAsSent(int $messageId): ?Message
    {
        return $this->updateStatus($messageId, 'sent');
    }

    public function markAsReceived(int $messageId): ?Message
    {
        return $this->updateStatus($messageId, 'received');
    }

    public function markAsRead(int $messageId): ?Message
    {
        return $this->updateStatus($messageId, 'read');
    }

    protected function updateStatus(int $messageId, string $status): ?Message
    {
        $message = Message::find($messageId);
        if (!$message) return null;

        $message->status = $status;
        $message->save();

        return $message;
    }

}
