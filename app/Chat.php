<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  // テーブルの属性を定義
  protected $fillable = [
    'participant_id', 'is_system_message', 'message',
  ];

  // テーブルの間の関係を記述
  public function participant() {
    return $this->belongsTo('App\ChatParticipant');
  }
}
