<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
  // 01. テーブルの属性を定義
  protected $fillable = [
    'user_id', 'chat_room_id', 'name', 'get_push_alarm',
  ];

  // 02. テーブルの間の関係を記述
  public function user() {
    return $this->belongsTo('App\User', 'user_id', 'id');
  }

  public function chatRoom() {
    return $this->belongsTo('App\ChatRoom');
  }

  public function chats() {
    return $this->hasMany('App\Chat', 'participant_id', 'id');
  }
}
