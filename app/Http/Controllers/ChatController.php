<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Muestra la sala de chat con los últimos mensajes.
     */
    public function index(): View
    {
        $messages = Message::with('user')
            ->orderByDesc('id')
            ->take(50)
            ->get()
            ->reverse()
            ->values()
            ->map(fn (Message $m) => [
                'id' => $m->id,
                'user_id' => $m->user_id,
                'name' => $m->user?->name ?? 'Usuario',
                'content' => $m->content,
                'created_at' => $m->created_at->toIso8601String(),
            ]);

        return view('chat.index', [
            'messages' => $messages,
        ]);
    }

    /**
     * Guarda un mensaje y lo transmite en tiempo real por Reverb.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message = $request->user()->messages()->create([
            'content' => trim($validated['content']),
        ]);

        $message->load('user');

        broadcast(new MessageSent($message));

        return response()->json([
            'id' => $message->id,
            'user_id' => $message->user_id,
            'name' => $message->user->name,
            'content' => $message->content,
            'created_at' => $message->created_at->toIso8601String(),
        ]);
    }
}
