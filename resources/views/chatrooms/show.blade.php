@extends('layouts.app')

@section('head')
@endsection

@section('content')
<section>
  {{-- メッセンジャ画面ー --}}
  <div>
    <h3>チャットリスト</h3>
    <ol id='ol_messages_list'>
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
    </ol>
  </div>

  {{-- チャット送信 --}}
  <div>
    {{ Form::open(['route' => ['chatrooms.chats.store', $chatroom], 'mehtod' => 'post']) }}
    {{ Form::text('message') }}
    {{ Form::submit('send') }}
    {{ Form::close() }}
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