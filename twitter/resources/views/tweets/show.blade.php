@extends('layouts.app')
@section('content')

    <body>
        <h1>ツイート詳細</h1>
        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <li class="error_message">{{ $error }}</li>
            @endforeach
        </ul>
        @endif  
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
                        @if ($reply->updated_at != $reply->created_at)
                            <small class="text-secondary">（編集済み）</small>
                        @endif
                    </p>
                </div>
                @if (Auth::id() === $reply->user->id)
                    <div class="tweet-dropdown">
                        <a class="dots-leader" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true">
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li>
                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#update-reply-modal-{{ $reply->id }}" href="#">編集する</a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" data-bs-toggle="modal"
                                    data-bs-target="#delete-reply-modal-{{ $reply->id }}" href="#">削除する</a>
                            </li>
                        </ul>
                    </div>
                    <!-- 編集用のモーダル -->
                    <div id="update-reply-modal-{{ $reply->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content update-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title">リプライ編集</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="update-reply-{{ $reply->id }}" method="POST"
                                        action="{{ route('tweets.updateReply', $reply->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <textarea class="tweet-textarea" type="text" id="update-reply-{{ $reply->id }}" name="reply">{{ $reply->reply }}</textarea>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                                    <button type="submit" form="update-reply-{{ $reply->id }}" class="btn btn-primary"
                                        data-bs-dismiss="modal">更新する</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 削除用のモーダル -->
                    <div class="modal fade" id="delete-reply-modal-{{ $reply->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">リプライを削除</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    リプライを削除してよろしいですか？
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                                    <button type="submit" form="delete-reply-{{ $reply->id }}" class="btn btn-danger"
                                        data-bs-dismiss="modal">削除する</button>
                                    <form id="delete-reply-{{ $reply->id }}" class="btn"
                                        action="{{ route('tweets.deleteReply', ['id' => $reply->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        @endforeach
    </body>
@endsection
