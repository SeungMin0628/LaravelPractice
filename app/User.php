<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // 01. テーブルの属性を定義
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 02. テーブルの間の関係を記述
    public function identifies() {
        return $this->hasMany('App\SocialIdentify');
    }

    public function friends() {
        return $this->hasMany('App\Friend', 'owner_id', 'id');
    }

    public function participants() {
        return $this->hasMany('App\ChatParticipant', 'user_id', 'id');
    }

    public function myRooms() {
        return $this->hasMany('App\ChatRoom', 'owner_id', 'id');
    }

    public function participatingRooms() {
        return $this->hasManyThrough('App\ChatRoom', 'App\ChatParticipant', 'user_id', 'id', 'id', 'chat_room_id');
    }

    // 03. インスタンスメッソドを実装
    public function selectRoomsList() {
        // return 
    }
}