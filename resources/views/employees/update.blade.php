<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新完了</title>
</head>
<body>
    <h1>ユーザ情報の更新が完了しました</h1>
    <p>新しい表示名：{{ $new_display_name }}</p>
    
    <p><a href="{{ route('books.index') }}">書籍一覧画面へ戻る</a></p> 
    </body>
</html>