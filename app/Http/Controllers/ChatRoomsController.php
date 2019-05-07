<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatRoom;
use App\User;
use App\ChatParticipant;
use App\Chat;

use Auth;
use Carbon;

// クラス名：ChatRoomsController
// 役割：チャットルームの作動に関する関数を定義
// 作成日：2019年4月22日

/******************************
  課題
    ＊　index 関数の作動方式をもっと綺麗にする
    ＊　ユーザーが自分の友達につけた名前がある場合、それを優先する

*******************************/
class ChatRoomsController extends Controller
{
  // インスタンス変数を定義

  // コンストラクタを定義
  public function __construct() {
    // set middleware
    $this->middleware('auth');
  }

  // インスタントメッソドを定義

  // チャットルームのリストを出力
  public function index() {
    // 01. チャットルームのデータを獲得
    $chatrooms = [];
    $rooms = User::find(Auth::user()->id)->participatingRooms()->get()->all();

    foreach($rooms as $room) {
      // 必要なデータを獲得
      $myParticipateInfo = $room->participants()->where('user_id', '=', Auth::user()->id)->first();
      $participants = $room->participantsWhereCurrentUser(Auth::user()->id, false)->with('user')->get()->all();

      $roomInfo = $myParticipateInfo->getChatRoomInfo();

      $chats = $room->chats()->orderBy('id', 'desc')->first();

      $chatrooms[] = (object)[
        'id' => $room->id,
        'name' => $roomInfo->name,
        'photos' => $roomInfo->photos,
        'message' => $chats->message,
        'last_chat_time' => $chats->created_at,
      ];
    };

    // 02. 最新のチャットの順番で整列
    usort($chatrooms, function($a, $b) {
      $aTime = Carbon::createFromFormat('Y-m-d H:i:s', $a->last_chat_time);
      $bTime = Carbon::createFromFormat('Y-m-d H:i:s', $b->last_chat_time);

      return $aTime->lt($bTime);
    });

    // 03. データをビュー側に返還
    return view('chatrooms.index')->with('chatrooms', $chatrooms);
  }

  /*
    役割：チャットルームの詳細を表示
    パラメータ：
        argId : チャットルームのID
  */
  public function show($argId) {
    // 01. 変数を定義
    $chats = [];
    $participants = [];

    // 02. そのチャットルームの参加者たちのリストを検索
    $chatroom = ChatRoom::find($argId);
    // $participateInfoOfUser = $chatroom->participantsWhereCurrentUser(Auth::user()->id, true)->first();
    $participantList = $chatroom->participantsWhereCurrentUser(Auth::user()->id, false)->get()->all();

    foreach($participantList as $participant) {
      $user = $participant->user;

      $participants[] = (object)[
        'id'    => $user->id,
        'name'  => $user->name,
        'photo' => $user->photo,
      ];
    }

    // 03. チャット履歴を検索
    $chatList = $chatroom->chatsWithBooleanIsThisCurrentUser(Auth::user()->id)->orderBy('created_at')->get()->all();
    foreach($chatList as $chat) {
      $info = (object)[
        'id'          => $chat->id,
        'message'     => $chat->message,
        'is_user'     => $chat->is_user,
        'created_at'  => $chat->created_at,
      ];

      if(!$chat->is_user) {
        $user = ChatParticipant::find($chat->participant_id)->user;

        $info->photo  = $user->photo;
        $info->name   = $user->name;
      }

      $chats[] = $info;
    }

    // 04. データをビュー側に伝送
    return view('chatrooms.show')->with([
      'chatroom_id'   => $argId,
      'chats'         => $chats,
      'participants'  => $participants,
      'chatroom'      => $argId,
    ]);
  }

  // 友達と１：１にチャットする
  /*
    = 友達とのチャットルームがある場合 => そのチャットルームを呼び出す
    = チャットルームがない場合 => 新しいチャットルームを生成
  */
  public function startChatByFriend($argFriendId) {
    // 01. その友達とのチャットルームを検索
    //    あったら、そのチャットルームを使う
    //    なかったら、新しいチャットルームを作る

  }

  // チャットルームを作るフォームを表示
  public function create() {
    $friends = User::find(Auth::user()->id)->friends()->with('user')->get()->all();

    return view('chatrooms.create')->with([
      'friends' => $friends,
    ]);
  }

  // チャットルームを生成
  /*
    セキュリティーのイシュー
    =  他人のアカウントのIDをわかれば、
       自分の友たちではない人も招くことができる
        => 自分の友たちではない場合、要請を拒否する
  */
  public function store(Request $request) {
    // 01. 変数の設定
    $user = User::find(Auth::user()->id);
    $friends = [$user];

    if(sizeof($request->post('friends')) <= 1) {
      // 招いた友達が1人しかいない場合
      // あの友達とのチャットルームがあるかどうかを確認する
    }


    foreach($request->post('friends')  as $friendId) {
      $friends[] = $user->friends()->where('friend_id', $friendId)->first()->user;
    }

    // 02. チャットルームを生成
    $chatroom = ChatRoom::create([
      'owner_id'  => $user->id,
      'is_opened' => $request->post('is_opened'),
    ]);

    // 03. 招いた友達をチャットの参加者として登録
    foreach($friends as $friend) {
      // 参加者の情報を登録
      $participant = ChatParticipant::create([
        'user_id'       => $friend->id,
        'chat_room_id'  => $chatroom->id,
      ]);

      // 招待メッセージを登録
      Chat::create([
        'participant_id'      => $participant->id,
        'message'             => "{$friend->name}様がお入りになりました。",
        'is_system_message'   => true,
      ]);
    }

    // 関数を終了し、元の画面に戻る
    return redirect(route('chatrooms.show', $chatroom->id));
  }
}
