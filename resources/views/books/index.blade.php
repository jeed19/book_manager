<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <p>従業員名：{{$session_data['employee_name']}}</p>

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

    <a href="/books/create">AAA</a>

</body>
</html>