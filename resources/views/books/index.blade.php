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
            </tr>
            @endforeach
        </tr>  
    </table>
    <br>
    <p><a href="/Books/update">ユーザ表示名変更</a></p>
</body>
</html>