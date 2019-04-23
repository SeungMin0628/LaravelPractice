<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ChatRoom;
use App\Chat;
use App\User;

use Auth;


class ChatsController extends Controller
{
  // コンストラクタを定義
  public function __construct() {
    $this->middleware('auth');
  }

  // インスタンスメッソドーを定義
  /*
    役割：
    パラメータ
        $request
            message : ユーザーが送ったメッセージ
        $argChatRoomId : チャットルームのID
  */
  public function store(Request $request, $argChatRoomId) {
    // 01. チャットが行われているチャットルームを獲得
    $chatroom = ChatRoom::find($argChatRoomId);
    $participant = $chatroom->participantsWhereCurrentUser(Auth::user()->id)->first();

    // 02. 必要な情報を登録
    Chat::create([
      'participant_id'    => $participant->id,
      'is_system_message' => false,
      'message'           => $request->post('message'),
    ]);

    // 03. データ保存の結果を返還
    return redirect(route('chatrooms.show', $argChatRoomId));
  }
}
