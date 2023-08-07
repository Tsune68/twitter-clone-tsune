@extends('layouts.app')
@section('content')

<body>
    <h1>いいねしたツイート</h1>
    @if($AllFavoriteTweets->isEmpty())
        <p>いいねされたツイートはまだありません</p>
    @endif
    @foreach ($AllFavoriteTweets as $tweet)
    <a 
        class="text-decoration-none tweet-card-link" 
        href="{{ route('tweets.show', ['id' => $tweet->id]) }}"
    >
        @include('components.tweet-card')
    </a>
    @endforeach
</body>
@endsection
