<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Chat;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $chat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $argChat)
    {
        //
        $this->chat = $argChat;
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
    return new PrivateChannel('chatroom.' . $this->chat->chatRoom->id);        
    }

    public function broadcastWith() {
        return [
            "chat" => [
                'message'       => $this->chat->message,
                'name'          => $this->chat->participant->user->name,
                'created_at'    => $this->chat->created_at,
            ],
        ];
    }
}
