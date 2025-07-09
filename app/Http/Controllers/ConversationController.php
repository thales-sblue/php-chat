<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Client;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'required|string'
        ]);

        $sender = auth()->user();
        $recipient = Client::where('cpf_cnpj', $request->cpf_cnpj)->first();

        if (!$sender) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        if (!$recipient) {
            return response()->json(['message' => 'Cliente não encontrado.'], 404);
        }

        $senderId = min($sender->id, $recipient->id);
        $recipientId = max($sender->id, $recipient->id);

        $conversation = Conversation::firstOrCreate(
            [
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
            ],
            [
                'last_message_content' => '',
                'last_message_time' => now(),
                'unread_count' => 0,
            ]
        );

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }

    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->with(['sender', 'recipient'])
            ->orderByDesc('last_message_time')
            ->get()
            ->map(function ($conversation) use ($userId) {
                $other = $conversation->sender_id === $userId
                    ? $conversation->recipient_id
                    : $conversation->sender;

                return [
                    'id' => $conversation->id,
                    'name' => $other->name ?? 'Desconhecido',
                    'last_message' => $conversation->last_message_content,
                    'last_message_time' => $conversation->last_message_time,
                    'unread_count' => $conversation->unread_count,
                ];
            });

        return response()->json($conversations);
    }
}
