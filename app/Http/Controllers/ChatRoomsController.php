<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChatRoom;
use App\User;

use Auth;

class ChatRoomsController extends Controller
{
  // コンストラクタを定義
  public function __construct() {

  }

  // インスタントメッソドを定義
  public function index() {
    $chatrooms = User::find(Auth::user()->id)->participatingRooms()->with('participants')->get()->all();

    return view('chatrooms.index')->with('chatrooms', $chatrooms);
  }

  public function show() {

  }

  public function create() {

  }

  public function store() {

  }
}
