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
        $receiver = Client::where('cpf_cnpj', $request->cpf_cnpj)->first();

        if (!$sender) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }
        if (!$receiver) {
            return response()->json(['message' => 'Cliente não encontrado.'], 404);
        }

        $ids = [$sender->id, $receiver->id];
        sort($ids);

        $conversation = Conversation::firstOrCreate([
            'sender_id' => min($sender->id, $receiver->id),
            'receiver_id' => max($sender->id, $receiver->id),
        ]);

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }

    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($conversation) use ($userId) {
                $other = $conversation->sender_id === $userId
                    ? $conversation->receiver
                    : $conversation->sender;

                return [
                    'id' => $conversation->id,
                    'name' => $other->name ?? 'Desconhecido'
                ];
            });

        return response()->json($conversations);
    }

}
