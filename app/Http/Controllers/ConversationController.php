<?php

namespace App\Http\Controllers;

use App\Services\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    protected ConversationService $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function start(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'required|string'
        ]);

        $senderId = auth()->id();

        $result = $this->conversationService->startConversation($request->cpf_cnpj, $senderId);

        if (!$result) {
            return response()->json(['message' => 'Cliente não encontrado ou usuário não autenticado.'], 404);
        }

        return response()->json($result);
    }

    public function index()
    {
        $userId = auth()->id();
        $conversations = $this->conversationService->getConversations($userId);

        return response()->json($conversations);
    }

    public function messages($id)
    {
        $userId = auth()->id();

        $result = $this->conversationService->getMessages($id, $userId);

        if (!$result) {
            return response()->json(['message' => 'Conversa não encontrada ou acesso negado.'], 404);
        }

        return response()->json($result);
    }
}
