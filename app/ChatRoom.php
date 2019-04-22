<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
  // 01. テーブルの属性を定義
  protected $fillable = [
    'owner_id', 'is_opened', 'name'
  ];

  // 02. テーブルの間の関係を記述
  public function owner() {
    return $this->belongsTo('App\User', 'owner_id', 'id');
  }

  public function chats() {
    return $this->hasMany('App\Chat');
  }

  public function chatParticipants() {
    return $this->hasMany('App\ChatParticipant');
  }
}
