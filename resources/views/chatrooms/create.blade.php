@extends('layouts.app')

@section('content')
<header id="header" class="">
  {{-- 検索機能 --}}
</header><!-- /header -->
<section>
  {{ Form::open(['route' => 'chatrooms.store', 'method' => 'post']) }}
  <div>
    <h4>チャットルームの属性を設定</h4>
    {{-- チャットルームの属性を設定 --}}
    <div>
      {{ Form::label('is_opened', '公開チャット') }}
      yes {{ Form::radio('is_opened', 1) }}
      no {{ Form::radio('is_opened', 0) }}
    </div>
  </div>
  <div>
    <h4>友達リスト</h4>
    {{-- 友達リスト --}}
    <div>
      <ul>
        @foreach($friends as $friend)
        <li>
          <div>
            <div>
              {{ Form::label( $friend->user->name ) }}
              {{ Form::checkbox('friends[]', $friend->user->id ) }}
            </div>
            <div>
              <div>
                {{ $friend->user->email }}
              </div>
            </div>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div>
    {{ Form::submit('submit') }}
  </div>
  {{ Form::close() }}
</section>
@endsection