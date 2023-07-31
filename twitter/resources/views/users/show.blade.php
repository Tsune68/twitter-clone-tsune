@extends('layouts.app')
@section('content')
<h1>ユーザー詳細</h1>
<div>
    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li class="error_message">{{ $error }}</li>
        @endforeach
    </ul>
    @endif
</div>

<div>
    <p class="border-bottom">ユーザーID:{{ $userInfo->id }}</p>
    <p class="border-bottom">ユーザー名: {{ $userInfo->name }}</p>
    <p class="border-bottom">メールアドレス: {{ $userInfo->email }}</p>
    <p class="border-bottom">
        フォロワー数:
        <a href="{{ route('users.followers', ['id' => $userInfo->id]) }}">
            {{ $userInfo->followers->count() }}フォロワー
        </a>
    </p>
    <p class="border-bottom">
        フォロー数:
        <a href="{{ route('users.follows', ['id' => $userInfo->id]) }}">
            {{ $userInfo->follows->count() }}フォロー
        </a>
    </p>
</div>

<body>
    <div class="text-center col-xs-12">
        <button type="button" class="btn btn-primary mx-3" data-bs-toggle="modal" data-bs-target="#test-modal">
            編集する
        </button>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
            アカウントを削除する
        </button>
    </div>

    <div id="test-modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content update-modal">
                <div class="modal-header">
                    <h5 class="modal-title">ユーザー編集</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update" method="POST" action="{{ route('users.update',['id' => $userInfo->id]) }}">
                        @csrf
                        @method('PUT')
                        <label for="name">ユーザー名</label>
                        <input type="text" id="name" name="name" value="{{ $userInfo->name }}">

                        <label for="email">メールアドレス</label>
                        <input type="text" id="email" name="email" value="{{ $userInfo->email }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submit" form="update" class="btn btn-primary" data-bs-dismiss="modal">更新する</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">アカウント削除</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    本当にアカウントを削除しますか？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submit" form="delete" class="btn btn-danger" data-bs-dismiss="modal">削除する</button>
                    <form id="delete" class="btn" action="{{ route('users.delete', ['id' => $userInfo->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection