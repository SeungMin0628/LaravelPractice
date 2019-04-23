@extends('layouts.app')

@section('head')
@endsection

@section('content')
{{-- 主なインタフェース --}}
<section>
  {{-- 検索インタフェース --}}
  <div>
    <input type="text" name="query" id='input_text_query'>
    <input type="button" id='input_button_query' value='search'>
  </div>

  {{-- チャットルームのリスト --}}
  <div>
    <ul>
      @foreach($chatrooms as $chatroom)
      <li name="li_chatroom" data-id="{{ $chatroom['id'] }}">
        {{-- ID --}}
        <div>
          {{ $chatroom['id'] }}
        </div>
        {{-- 名前 --}}
        <div>
          @if(is_null($chatroom['name']))
          NULL
          @else
          {{ $chatroom['name'] }}
          @endif
        </div>
        {{-- プロフィル写真 --}}
        <div>
          profile
        </div>
        {{-- 最新のチャット --}}
        <div>
          {{ $chatroom['message'] }}
        </div>
        <div>
          {{ $chatroom['last_chat_time'] }}
        </div>
      </li>
      @endforeach
    </ul>
  </div>

  {{-- チャットルーム --}}
  <div>
    {{-- メッセンジャ画面ー --}}
    <div>
      <ol id='ol_messages_list'>
      </ol>
    </div>
    {{-- 参加者リスト --}}
    <div>
      <ol id='ol_participants_list'>
      </ol>
    </div>
  </div>
</section>

{{-- デスクトップの場合：選んだチャットを表示 --}}
<section>

</section>
@endsection