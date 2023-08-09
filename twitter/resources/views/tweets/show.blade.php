@extends('layouts.app')
@section('content')

    <body>
        <h1>ツイート詳細</h1>
        @include('components.tweet-card')
        @foreach ($replies as $reply)
            <div class="card tweet-card text-dark bg-light mb-3">
                <div class="card-body">
                    <p class="card-title fw-bolder">
                        {{ $reply->user->name }}
                        <span class="text-secondary">{{ $reply->created_at }}</span>
                    </p>
                    <p class="card-text">
                        {{ $reply->reply }}
                    </p>
                </div>
            </div>
        @endforeach
    </body>
@endsection
