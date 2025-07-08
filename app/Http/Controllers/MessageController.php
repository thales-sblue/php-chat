<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|exists:clients,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->to,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return response()->json(['message' => $message], 201);
    }

    public function list()
    {
        $messages = Message::where('from', auth()->id())->orWhere('to', auth()->id())->get();
        return response()->json($messages);
    }
}
