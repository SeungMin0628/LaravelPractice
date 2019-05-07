@extends('layouts.app')

@section('head')
<script>
  $(window).on("load", function() {
    // メッセージのリストのスクロールバーを最下にする
    function scrollUpdate() {
      var divMessageArea  = $("#div-message_area");
      var ulChats         = $('#ul-chats');

      divMessageArea.scrollTop(ulChats.height());
    }

    // メッセージを画面に出力
    function printOutMessage(message, name, created_at) {
      // 01. 変数を定義
      var ulChats   = $('#ul-chats');
      var liElement = $("<li></li>");

      liElement.append(`<b>${name}</b>`);
      liElement.append(`<span>${message}</span>`);
      liElement.append(`<span>${created_at}</span>`);

      ulChats.append(liElement);      
      scrollUpdate();
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

    scrollUpdate();
  });  
</script>

<style>
    #div-message_area {
        width:100%;
        height: 400px;
        border: 1px solid lightgray;
        margin: 10px 0;
        padding: 5px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header">{{ $chatroom_name }}</div>

              <div class="card-body">
                  @if (session('status'))
                      <div class="alert alert-success" role="alert">
                          {{ session('status') }}
                      </div>
                  @endif

                  <div id="div-message_area">
                      {{-- メッセンジャ画面ー --}}
                      <ul id="ul-chats">
                          @foreach($chats as $chat)
                          <li>
                              <b>
                                @if($chat->is_user)
                                me
                                @else
                                {{ $chat->name }}
                                @endif
                              </b>
                              <span>{{ $chat->message }}</span>
                              <span>{{ $chat->created_at }}</span>
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
                            {{ $participant->name }}
                          </div>
                          <div>
                            {{ $participant->photo }}
                          </div>
                        </li>
                      @endforeach
                    </ol>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection