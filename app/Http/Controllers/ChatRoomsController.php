<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatRoom;
use App\User;
use App\ChatParticipant;

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

    foreach($rooms as $value) {
      // 必要なデータを獲得
      $myParticipateInfo = $value->participants()->where('user_id', '=', Auth::user()->id)->first();
      $participants = $value->participantsWhereCurrentUser(Auth::user()->id, false)->with('user')->get()->all();

      // チャットルームの名前を獲得
      $name = '';
      if(!is_null($myParticipateInfo->name)) {
        // 使用者がチャットルームの名前を指定した場合
        $name = $value->name;
      } else if (!is_null($value->name)) {
        // そのチャットルームの名前が定まった場合
        $name = $value->name;
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

      $chats = $value->chats()->orderBy('created_at', 'desc')->first();

      $chatrooms[] = [
        'id' => $value->id,
        'name' => $name,
        'photos' => $photos,
        'message' => $chats->message,
        'last_chat_time' => $chats->created_at,
      ];
    };

    // 02. 最新のチャットの順番で整列
    usort($chatrooms, function($a, $b) {
      $aTime = Carbon::create($a['last_chat_time']);
      $bTime = Carbon::create($b['last_chat_time']);

      return $aTime->gt($bTime);
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

      $participants[] = [
        'id'    => $user->id,
        'name'  => $user->name,
        'photo' => $user->photo,
      ];
    }

    // 03. チャット履歴を検索
    $chatList = $chatroom->chatsWithBooleanIsThisCurrentUser(Auth::user()->id)->orderBy('created_at')->get()->all();
    foreach($chatList as $chat) {
      $info = [
        'id'          => $chat->id,
        'message'     => $chat->message,
        'is_user'     => $chat->is_user,
        'created_at'  => $chat->created_at,
      ];

      if(!$chat->is_user) {
        $user = ChatParticipant::find($chat->participant_id)->user;

        $info['photo'] = $user->photo;
        $info['name'] = $user->name;
      }

      $chats[] = $info;
    }

    // 04. データをビュー側に伝送
    return response()->json([
      'chats'         => $chats,
      'participants'  => $participants,
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

  }

  // チャットルームを生成
  public function store() {

  }
}