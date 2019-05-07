<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

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

  // 03. インスタンスメソッドを定義
  public function getChatRoomInfo() {
    // 変数を定義
    $chatRoom = $this->chatRoom;
    $participants = $chatRoom->participantsWhereCurrentUser(Auth::user()->id, false)->with('user')->get()->all();

    // チャットルームの名前を獲得
    $name = '';
    if(!is_null($this->name)) {
       // 使用者がチャットルームの名前を指定した場合
       $name = $this->name;
    } else if (!is_null($chatRoom->name)) {
      // そのチャットルームの名前が定まった場合
      $name = $chatRoom->name;
    } else {
     // 定まった名前がない場合 => そのチャットルームの参加者の名前で
      $names = [];
    }

    // プロファイル写真を獲得
    $photos = [];
    foreach($participants as $participant) {
      $photos[] = $participant->user->photo;
       if(isset($names)) {
        $names[] = $participant->user->name;
      }
    }

    if(isset($names)) {
      $name = implode(', ', $names);
    }
    
    return (object)[
      'name'    => $name,
      'photos'  => $photos,
    ];
  }
}
