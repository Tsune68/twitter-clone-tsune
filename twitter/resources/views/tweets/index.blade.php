@extends('layouts.app')
@section('content')

<body>
    <h1>ツイート一覧</h1>
    @foreach ($allTweets as $tweet)
    <a class="text-decoration-none tweet-card-link" href="{{ route('tweets.show', ['id' => $tweet->id]) }}">
        @include('components.tweet-card')
    </a>
    @endforeach
</body>
@endsection