<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ラクーン社所有 書籍一覧</title>
</head>
<body>
    <p>ようこそ、{{$session_data['display_name']}} さん！</p>

    <table>
        <tr><th>ISBN</th><th>タイトル</th><th>著者名</th><th>画像</th>
            @foreach($records as $record)
            <tr>
                <td>{{$record->isbn}}</td>
                <td>{{$record->book_name}}</td>
                <td>{{$record->author_name}}</td>
                <td>{{$record->cover_image}}</td>

                <td>
                    <a href="{{ route('reviews.edit', ['isbn' => $record->isbn]) }}">感想を書く/編集</a>
                </td>
            </tr>
            @endforeach
        </tr>  
    </table>

    <a href="/books/create">書籍作成</a>

       <form action="/books/show" method="POST">
            <input type="hidden" name="isbn" value="{{$record->isbn}}">
            <button type="submit" class="btn-show"></button>
        </form>

    <br>
    <ul>
    <li><a href="/Employees/edit">ユーザ表示名・パスワード変更</a></li>
    
    @if(session('session_data')['can_unlock'])
    <li><a href="/Employees/index" style="color: blue; font-weight: bold;">【管理者用】アカウントロック解除画面</a></li>
    @endif
</ul>
</body>
</html>