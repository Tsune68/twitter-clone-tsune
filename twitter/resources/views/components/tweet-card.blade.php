<div class="card tweet-card text-dark bg-light mb-3">
    <div class="card-body">
        <p class="card-title fw-bolder">
            {{ $tweet->user->name }}
            <span class="text-secondary">{{ $tweet->created_at }}</span>
        </p>
        <p class="card-text">
            {{ $tweet->tweet }}
            @if ($tweet->updated_at != $tweet->created_at)
                <small class="text-secondary">（編集済み）</small>
            @endif
        </p>
        @if ($tweet->image_path)
            <img src="{{ asset($tweet->image_path) }}" class="tweet-image"/>
        @endif
        <div class="favorite-marke">
            <a class="js-favorite-toggle {{ $tweet->isFavorite ? 'loved' : '' }}" href=""
                data-tweetid="{{ $tweet->id }}">
                <i class="fas fa-heart"></i>
            </a>
            <span class="favoritesCount">{{ $tweet->favorites()->count() }}</span>
            <a class="dropdown-item reply-icon" data-bs-toggle="modal" data-bs-target="#reply-modal-{{ $tweet->id }}"
                href="#">
                <i class="far fa-comment"></i>
                <span class="favoritesCount">{{ $tweet->replies()->count() }}</span>
            </a>
        </div>
    </div>
    @if (Auth::id() === $tweet->user->id)
        <div class="tweet-dropdown">
            <a class="dots-leader" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="true">
            </a>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li>
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#test-modal-{{ $tweet->id }}"
                        href="#">編集する</a>
                </li>
                <li>
                    <a class="dropdown-item text-danger" data-bs-toggle="modal"
                        data-bs-target="#delete-modal-{{ $tweet->id }}" href="#">削除する</a>
                </li>
            </ul>
        </div>
        <!-- 編集用のモーダル -->
        <div id="test-modal-{{ $tweet->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content update-modal">
                    <div class="modal-header">
                        <h5 class="modal-title">ツイート編集</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="update-{{ $tweet->id }}" method="POST"
                            action="{{ route('tweets.update', $tweet->id) }}">
                            @csrf
                            <textarea class="tweet-textarea" type="text" id="tweet" name="tweet">{{ $tweet->tweet }}</textarea>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" form="update-{{ $tweet->id }}" class="btn btn-primary"
                            data-bs-dismiss="modal">更新する</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 削除用のモーダル -->
        <div class="modal fade" id="delete-modal-{{ $tweet->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ツイート削除</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ツイートを削除してよろしいですか？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" form="delete-{{ $tweet->id }}" class="btn btn-danger"
                            data-bs-dismiss="modal">削除する</button>
                        <form id="delete-{{ $tweet->id }}" class="btn"
                            action="{{ route('tweets.delete', ['id' => $tweet->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- リプライ用のモーダル -->
    <div id="reply-modal-{{ $tweet->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content update-modal">
                <div class="modal-header">
                    <h5 class="modal-title">コメントする</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reply-{{ $tweet->id }}" method="POST"
                        action="{{ route('tweets.reply', ['id' => $tweet->id]) }}">
                        @csrf
                        <textarea class="tweet-textarea" type="text" id="reply" name="reply"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submit" form="reply-{{ $tweet->id }}" class="btn btn-primary"
                        data-bs-dismiss="modal">送信する</button>
                </div>
            </div>
        </div>
    </div>
</div>
