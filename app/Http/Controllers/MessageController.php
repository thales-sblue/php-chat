<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Jobs\SendMessageJob;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
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
        $message = $this->messageService->send($validated, $senderId);

        SendMessageJob::dispatch($message->id);

        return response()->json(['message' => 'Mensagem enviada com sucesso.'], 202);
    }

    public function list($conversationId)
    {
        $messages = $this->messageService->listByConversation($conversationId);

        return response()->json($messages);
    }

    public function markRead($id)
    {
        $message = $this->messageService->markAsRead($id);

        if (!$message) {
            return response()->json(['error' => 'Mensagem não encontrada'], 404);
        }

        return response()->json($message);
    }

}
