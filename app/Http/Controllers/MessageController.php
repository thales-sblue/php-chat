<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use App\Jobs\SendMessageJob;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'recipient_id' => 'required|exists:clients,id',
            'content' => 'required|string',
            'priority' => 'nullable|in:normal,urgent',
        ]);

        $senderId = auth()->id();
        $message = $this->chatService->sendMessage($validated, $senderId);

        SendMessageJob::dispatch($message->id);

        return response()->json(['message' => 'Mensagem enviada com sucesso.'], 202);
    }

    public function list($conversationId)
    {
        $messages = $this->chatService->listMessagesByConversation($conversationId);
        return response()->json($messages);
    }

    public function markRead($id)
    {
        $message = $this->chatService->markMessageAsRead($id);

        if (!$message) {
            return response()->json(['error' => 'Mensagem não encontrada'], 404);
        }

        return response()->json($message);
    }
}
