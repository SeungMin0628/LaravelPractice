@extends('layouts.app')

@section('head')
<script>
  $(window).on("load", function() {
    // メッセージを画面に出力
    function printOutMessage(message, name, created_at) {
      // 01. 変数を定義
      var olMessagesList  = $('#ol-messages-list');
      var liElement       = $("<li></li>");

      liElement.append(`<div>${message}</div>`);
      liElement.append(`<div>${name}</div>`);
      liElement.append(`<div>${created_at}</div>`);

      olMessagesList.append(liElement);
    }

    // 受信したイベントを処理
    window.Echo.private("chatroom." + {{ $chatroom_id }})
    .listen("SendMessage", (e) => {
      var chat = e.chat;

      printOutMessage(chat.message, chat.name, chat.created_at);
    });

    // 自分のメッセージを送信
    $("#input-button-send").click(function (e) { 
      e.preventDefault();
      
      // 01. 変数を定義
      var message = $("#input-text-message").val();

      if(message.length <= 0) {
        alert('please input message!');
        return;
      }

      // 02. メッセージを送信
      $.ajax({
        type: "POST",
        url: "{{ route('chatrooms.chats.store', $chatroom_id) }}",
        data: {
            '_token': '{{ csrf_token() }}',
            'message': message
        },
        dataType: "json",
        success: function (response) {
          $('#input-text-message').val('');

          var today = new Date();
          var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
          var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
          var dateTime = date+' '+time;

          printOutMessage(message, 'me', dateTime);
        }
      });
    });
  });  
</script>
@endsection

@section('content')
<section>
  {{-- メッセンジャ画面ー --}}
  <div>
    <h3>チャットリスト</h3>
    <ul id='ol-messages-list'>
      @foreach($chats as $chat)
      <li>
        <div>
          @if($chat['is_user'])
          me
          @else
          {{ $chat['name'] }}
          @endif
        </div>
        <div>
          {{ $chat['message'] }}
        </div>
        <div>
          {{ $chat['created_at'] }}
        </div>
      </li>
      @endforeach
    </ul>
  </div>

  {{-- チャット送信 --}}
  <div>
    <input type="text" name="message" id="input-text-message">
    <input type="button" id="input-button-send" value="send">
  </div>

  {{-- 参加者リスト --}}
  <div>
    <h3>参加者リスト</h3>
    <ol id='ol_participants_list'>
      @foreach($participants as $participant)
        <li>
          <div>
            {{ $participant['name'] }}
          </div>
          <div>
            {{ $participant['photo'] }}
          </div>
        </li>
      @endforeach
    </ol>
  </div>
</section>
@endsection