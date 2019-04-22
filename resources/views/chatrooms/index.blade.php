@extends('layouts.app')

@section('header')
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
    @foreach($chatrooms as $chatroom)
    @endforeach
  </div>
</section>

{{-- デスクトップの場合：選んだチャットを表示 --}}
<section>
  
</section>
@endsection