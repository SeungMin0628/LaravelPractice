<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Friend;

use Auth;

class FriendsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    //
    public function store(Request $request) {
        // 01. リクエストの有効性を検証

        // 02. 変数を定義
        $userId = $request->post('user_id');

        // 03. 友達追加したユーザーが既に友達である場合、メソッドを終了する
        if(Auth::user()->isFriend($userId)) {
            return response()->json((object)[
                'status'    => false,
            ]);
        }

        // 04. データを保存
        Friend::create([
            'owner_id'  => Auth::user()->id,
            'friend_id' => $userId,
        ]);

        // 05. リスポンス
        return response()->json((object)[
            'status'    => true,
        ]); 
    }
}
