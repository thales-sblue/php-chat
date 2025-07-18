<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function start(Request $request)
    {
        $request->validate([
            'cpf_cnpj' => 'required|string'
        ]);

        $senderId = auth()->id();

        $result = $this->chatService->startConversation($request->cpf_cnpj, $senderId);

        if (!$result) {
            return response()->json(['message' => 'Cliente não encontrado ou usuário não autenticado.'], 404);
        }

        return response()->json($result);
    }

    public function index()
    {
        $userId = auth()->id();
        $conversations = $this->chatService->getUserConversations($userId);

        return response()->json($conversations);
    }

    public function messages($id)
    {
        $userId = auth()->id();

        $result = $this->chatService->getMessages($id, $userId);

        if (!$result) {
            return response()->json(['message' => 'Conversa não encontrada ou acesso negado.'], 404);
        }

        return response()->json($result);
    }
}
