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

  public function participants() {
    return $this->hasMany('App\ChatParticipant');
  }

  // 03. インスタンスメッソドを定義
  public function getUsersName($argCurrentUserId) {
    return $this->participants()->with('user')->whereNot('user_id', $argCurrentUserId);
  }
}
