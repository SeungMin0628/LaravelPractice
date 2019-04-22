<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
  // 01. テーブルの属性を定義
  protected $fillable = [
    'user_id', 'chat_room_id', 'start_chat_id', 'name', 'get_push_alarm',
  ];

  // 02. テーブルの間の関係を記述
  public function user() {
    return $this->belongsTo('App\User');
  }

  public function chatRoom() {
    return $this->belongsTo('App\ChatRoom');
  }

  public function startChat() {
    return $this->hasOne('App\Chat', 'start_chat_id', 'id');
  }
}
