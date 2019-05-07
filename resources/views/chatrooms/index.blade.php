@extends('layouts.app')

@section('head')
<script>
  $(window).on('load', function () {
    // 友達を探すインプットイベントを設定
    $("#input-text-email").on('input', function (e) {
      e.preventDefault();

      // 01. 変数を定義
      var ulSearchResult = $('#ul-friend_search_result');
      
      // 何も入力されなかった場合、検索結果を消しメソッドを終了する
      if(e.target.value.length <= 0) {
        ulSearchResult.empty();
        return;
      }

      // 02. 非同期通信を設定
      $.ajax({
        type: "get",
        url: '{{ route("users.search") }}',
        data: {
          'email': e.target.value,
        },
        dataType: "json",
        success: function (response) {
          // 通信成功した場合、検索結果を出力する
          ulSearchResult.empty();

          // console.log(response.users);

          response.users.forEach(element => {
            var liElement = $('<li></li>');

            liElement.append(`<b>${element.name}</b>`);
            liElement.append(`<div>${element.email}</div>`);

            if(element.is_friend) {
              //　友達である場合、そのメッセージを出力
              liElement.append(`<div>友達です。</div>`);
            } else {
              // 友達ではない場合、クリックしたら友達登録する
              liElement.dblclick(function (e) { 
                e.preventDefault();
                var message = `${element.name}様を友達にしますか?`;
                
                if(confirm(message)) {
                    $.ajax({
                      type: "post",
                      url: "{{ route('friends.store') }}",
                      data: {
                        _token: "{{ csrf_token() }}",
                        user_id: element.id
                      },
                      dataType: "json",
                      success: function (response) {
                        location.reload();
                      }
                    });
                }
              });
            }

            ulSearchResult.append(liElement);
          });
        }
      });
    });
  });
</script>
@endsection

@section('content')
{{-- 主なインタフェース --}}
<section style="float:left">
  {{-- 検索インタフェース --}}
  <div>
    <div>
      <h4>チャットルーム検索</h4>
    </div>
    <div>
      <input type="text" name="query" id='input_text_query'>
      <input type="button" id='input_button_query' value='search'>
    </div>
  </div>

  {{-- チャットルームのリスト --}}
  <div>
    <h4>チャットルームリスト</h4>
    <div>
      <a href="{{ route('chatrooms.create') }}" title="">create chat room</a>
    </div>
    <ul>
      @foreach($chatrooms as $chatroom)
      <li name="li_chatroom" data-id="{{ $chatroom->id }}">
        {{-- ID --}}
        <div>
          <a href="{{ route('chatrooms.show', $chatroom->id) }}" title="">chat</a>
        </div>
        {{-- 名前 --}}
        <div>
          @if(is_null($chatroom->name))
          NULL
          @else
          {{ $chatroom->name }}
          @endif
        </div>
        {{-- プロフィル写真 --}}
        <div>
          profile
        </div>
        {{-- 最新のチャット --}}
        <div>
          {{ $chatroom->message }}
        </div>
        <div>
          {{ $chatroom->last_chat_time }}
        </div>
      </li>
      @endforeach
    </ul>
  </div>
</section>

{{-- 選んだチャットを表示 --}}
<section>

</section>

{{-- 接続した友達＆友達の最近の活動時間を表示 --}}
<section style="float:right">
  <div>
    <h4>新しい友達を探そう！</h4>
    <input type="text" name="email" id="input-text-email">
    <input type="button" value="search">
    <div>
      <ul id="ul-friend_search_result">

      </ul>
    </div>
  </div>
  <div>
    <h4>友達リスト</h4>
    <div>
      <ul>
        @foreach($friends as $friend)
          <li>
            <b>
              @if(!is_null($friend->name))
                {{ $friend->name }}
              @else
                {{ $friend->user->name }}
              @endif
            </b>
            <span>
              @if($friend->user->isOnline())
                O
              @else
                X
              @endif
            </span>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</section>
@endsection