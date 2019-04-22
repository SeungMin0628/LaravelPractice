<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
  // 01. テーブルの属性を定義
  protected $fillable = [
    'owner_id', 'friend_id', 'nickname',
  ];

  // 02. テーブルの間の関係を記述
  // public function owner() {
  //   return $this->belongsTo('App\User', 'owner_id', 'id');
  // }
}
