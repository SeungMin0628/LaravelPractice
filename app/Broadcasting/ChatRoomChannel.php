<?php

namespace App\Broadcasting;

use App\User;
use App\ChatParticipant;

class ChatRoomChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, $chatroomId)
    {        
        return ChatParticipant::where(['user_id' => $user->id, 'chat_room_id' => $chatroomId])->exists();        
    }
}
