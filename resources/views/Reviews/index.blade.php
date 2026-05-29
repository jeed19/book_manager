<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイレビュー一覧</title>
</head>
<body>
    <h1>マイレビュー（一覧）</h1>

    <a href="/books/index">書籍一覧に戻る</a>
    <hr>

    @foreach($reviews as $r)
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <p><strong>対象のISBN:</strong> {{ $r->isbn }}</p>
            <p><strong>おすすめ度:</strong> {{ $r->recommended_level }}</p>
            <p><strong>コメント:</strong> {{ $r->comment }}</p>
            
            <a href="{{ route('reviews.show', ['id' => $r->id]) }}">詳細を見る / 削除する</a>
        </div>
    @endforeach

</body>
</html>