@extends('layouts.app')
@section('content')

<body>
    <h1>ツイート一覧</h1>
    <div class="search-wrap">
        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <li class="error_message">{{ $error }}</li>
            @endforeach
        </ul>
        @endif            
        <form class="search-form" method="GET" action="{{ route('tweets.index') }}">
            <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="キーワードを入力">
            <button class="btn btn-primary search-btn" type="submit" id="button-addon2">検索</button>
        </form>
    </div>
    @foreach ($allTweets as $tweet)
    <a class="text-decoration-none tweet-card-link" href="{{ route('tweets.show', ['id' => $tweet->id]) }}">
        @include('components.tweet-card')
    </a>
    @endforeach
</body>
@endsection
