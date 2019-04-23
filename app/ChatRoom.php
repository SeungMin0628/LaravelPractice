<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use DB;

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

  public function participants() {
    return $this->hasMany('App\ChatParticipant');
  }

  // このチャットルームの参加者たちの中、ユーザーを除いて検索
  public function participantsWhereCurrentUser($argUserId, $argFlag = true) {
    $sign = $argFlag ? '=' : '<>';

    return $this->participants()->where('user_id', $sign, $argUserId);
  }

  public function chats() {
    return $this->hasManyThrough('App\Chat', 'App\ChatParticipant', 'chat_room_id', 'participant_id', 'id', 'id');
  }

  public function chatsWithBooleanIsThisCurrentUser($argUserId) {
    return $this->chats()->select(DB::raw("chats.*, case participant_id when {$argUserId} then true else false end as 'is_user'"));
  }

  // 03. インスタンスメッソドを定義
  // public function getUsersName($argCurrentUserId) {
  //   return $this->participants()->with('user')->whereNot('user_id', $argCurrentUserId);
  // }
}
