<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
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

        return response()->json(['message' => $message], 201);
    }

    public function list($conversationId)
    {
        $messages = $this->messageService->listByConversation($conversationId);

        return response()->json($messages);
    }
}
