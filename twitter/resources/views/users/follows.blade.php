@extends('layouts.app')
@section('content')
<body>
    <h1>フォロワー一覧</h1>
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
                @foreach ($allFollows as $follow)
                <tr>
                    <td>{{ $follow->id }}</td>
                    <td>
                        {{ $follow->name }}
                    </td>
                    <td>{{ $follow->email }}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</body>
@endsection
