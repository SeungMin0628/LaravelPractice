<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ChatsController extends Controller
{
  // コンストラクタを定義
  public function __construct() {
    $this->middleware('auth');
  }

  // インスタンスメッソドーを定義
  public function index() {
    // return view('chats.index');
  }
}
