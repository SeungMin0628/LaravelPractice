<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  // テーブルの属性を定義
  protected $fillable = [
    'user_id', 'chat_room_id', 'is_system_message', 'message',
  ];

  // テーブルの間の関係を記述
  public function user() {
    return $this->belongsTo('App\User');
  }

  public function chatRoom() {
    return $this->belongsTo('App\ChatRoom');
  }

  public function chatParticipant() {
    return $this->belongsTo('App\ChatParticipant');
  }
}
