<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatRoom;
use App\User;

use Auth;
use Carbon;

class ChatRoomsController extends Controller
{
  // コンストラクタを定義
  public function __construct() {
    $this->middleware('auth');
  }

  // インスタントメッソドを定義
  public function index() {
    // 01. チャットルームのデータを獲得
    $userId = Auth::user()->id;
    $chatrooms = [];
    $rooms = User::find($userId)->participatingRooms()->get()->all();

    foreach($rooms as $value) {
      // 必要なデータを獲得
      $myParticipateInfo = $value->participants()->where('user_id', '=', $userId)->first();
      $participants = $value->participants()->where('user_id', '<>', $userId)->with('user')->get()->all();

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
      $profiles = [];
      foreach($participants as $participant) {
        // $profiles[] = $participant->user->profile;

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
        // 'profiles' => $profiles,
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

    return view('chatrooms.index')->with('chatrooms', $chatrooms);
  }

  public function show() {
    
  }

  public function create() {

  }

  public function store() {

  }
}
