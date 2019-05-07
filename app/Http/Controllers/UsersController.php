<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    //
    public function search(Request $request) {
        // 01. リクエストの内容を検証

        // 02. 変数を定義
        $userEmail = $request->get('email');

        // 03. ユーザーを探す
        $users = User::searchWhereEmail($userEmail)->get(['id', 'name', 'email', 'is_friend'])->all();

        // 04. リスポンス
        return response()->json([
            'users'  => $users,
        ]);
    }
}
