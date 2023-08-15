@extends('layouts.app')
@section('content')

<body>
    <h1>ツイート投稿</h1>
    <div>
        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <li class="error_message">{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>


    <form id="store" method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <label class="form-label" for="tweet">ツイート内容</label>
            <textarea class="form-control" name="tweet" id="tweet"></textarea>
        </div>
        <div>
            <input type="file" name="image">
        </div>
        <button type="submit" form="store" class="btn btn-primary">ツイートする</button>
    </form>
</body>
@endsection
