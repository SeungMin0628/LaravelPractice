<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Chat extends Model
{
  // テーブルの属性を定義
  protected $fillable = [
    'participant_id', 'is_system_message', 'message',
  ];

  // テーブルの間の関係を記述
  public function participant() {
    return $this->belongsTo('App\ChatParticipant', 'participant_id', 'id');
  }

  // インスタンスメッソドを定義
  public function isUser($argParticipantId) {
    return $this->select(DB::raw("chats.*, case participant_id when {$argUserId} then true else false end as 'is_user'"));
  }
}
