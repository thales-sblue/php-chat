<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'recipient_id' => 'required|exists:clients,id',
            'content' => 'required|string',
            'priority' => 'nullable|in:normal,urgent',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => auth()->id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
            'priority' => $request->priority ?? 'normal',
            'status' => 'queued',
        ]);

        return response()->json(['message' => $message], 201);
    }

    public function list($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
