<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('/logout','Auth\LoginController@logout');

/*
  ソーシャルライトの認証に使うURL
*/
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('socialite');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('socialite.callback');

// chatsのルーティングを設定
Route::get('/', 'ChatRoomsController@index');
Route::resource('chatrooms', 'ChatRoomsController');