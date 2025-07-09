<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Client;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function startConversation(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'required|string'
        ]);

        $sender = auth()->user();
        $receiver = Client::where('cpf_cnpj', $request->cpf_cnpj)->first();

        if (!$receiver) {
            return response()->json(['message' => 'Cliente não encontrado.'], 404);
        }

        $conversation = Conversation::firstOrCreate([
            'sender_id' => min($sender->id, $receiver->id),
            'receiver_id' => max($sender->id, $receiver->id),
        ]);

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }
}
