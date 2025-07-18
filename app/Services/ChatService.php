<?php

namespace App\Services;

use App\Services\ConversationService;
use App\Services\MessageService;
use Illuminate\Support\Collection;
use App\Models\Message;

class ChatService
{
    protected ConversationService $conversationService;
    protected MessageService $messageService;

    public function __construct(
        ConversationService $conversationService,
        MessageService $messageService
    ) {
        $this->conversationService = $conversationService;
        $this->messageService = $messageService;
    }

    public function startConversation(string $cpfCnpj, int $senderId): ?array
    {
        return $this->conversationService->startConversation($cpfCnpj, $senderId);
    }

    public function getUserConversations(int $userId): Collection
    {
        $this->messageService->markAllAsReceived($userId);
        return $this->conversationService->getConversations($userId);
    }

    public function getMessages(int $conversationId, int $userId): ?array
    {
        $this->messageService->markAllAsRead($conversationId, $userId);
        return $this->conversationService->getMessages($conversationId, $userId);
    }

    public function sendMessage(array $data, int $senderId): Message
    {
        return $this->messageService->send($data, $senderId);
    }

    public function listMessagesByConversation(int $conversationId): Collection
    {
        return $this->messageService->listByConversation($conversationId);
    }

    public function markMessageAsSent(int $messageId): ?Message
    {
        return $this->messageService->markAsSent($messageId);
    }

    public function markMessageAsReceived(int $messageId): ?Message
    {
        return $this->messageService->markAsReceived($messageId);
    }

    public function markMessageAsRead(int $messageId): ?Message
    {
        return $this->messageService->markAsRead($messageId);
    }

    public function markAllAsRead(int $conversationId, int $userId): void
    {
        $this->messageService->markAllAsRead($conversationId, $userId);
    }

    public function markAllAsReceived(int $userId): void
    {
        $this->messageService->markAllAsReceived($userId);
    }
}
