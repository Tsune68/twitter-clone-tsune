@extends('layouts.app')
@section('content')

<body>
    <h1>ユーザー一覧</h1>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">名前</th>
                    <th scope="col">メールアドレス</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allUsers as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        {{ $user->name }}
                        @if (Auth::id() !== $user->id)
                            @if (Auth::user()->follows->contains('id', $user->id))
                            <form id="follow-{{ $user->id }}" method="POST" action="{{ route('users.unfollow', ['id' => $user->id]) }}">
                                @csrf
                                <button type="submit">
                                    フォロー外す
                                </button>
                            </form>
                            @else
                            <form id="follow-{{ $user->id }}" method="POST" action="{{ route('users.follow', ['id' => $user->id]) }}">
                                @csrf
                                <button type="submit">
                                    フォローする
                                </button>
                            </form>
                            @endif
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
@endsection