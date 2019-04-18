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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/*
  ソーシャルライトの認証に使うURL
*/
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('socialite');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('socialite.callback');