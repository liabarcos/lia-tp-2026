<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento que se emite por WebSockets (Reverb) cada vez que
 * un usuario envía un mensaje a la sala de chat.
 */
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
    }

    /**
     * Canal público donde se publican los mensajes.
     */
    public function broadcastOn(): array
    {
        return [new Channel('chat')];
    }

    /**
     * Nombre del evento que escucha el frontend (Echo).
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Datos que viajan por el WebSocket hacia los navegadores.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'name' => $this->message->user->name ?? 'Usuario',
            'content' => $this->message->content,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
