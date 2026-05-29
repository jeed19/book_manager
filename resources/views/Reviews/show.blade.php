<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レビュー詳細</title>
</head>
<body>
    <h1>レビュー詳細画面</h1>

    <p><strong>書籍ISBN:</strong> {{ $book->isbn }}</p>
    <p><strong>おすすめ度:</strong> {{ $review->recommended_level }}</p>
    <p><strong>コメント:</strong> {{ $review->comment }}</p>
    
    <hr>
    
    <a href="{{ route('reviews.edit', ['isbn' => $book->isbn]) }}">このレビューを編集する</a>
    
    <br><br>

    <form action="{{ route('reviews.delete', ['id' => $review->id]) }}" method="POST" onsubmit="return confirm('本当に削除してよろしいですか？');">
        @csrf
        <button type="submit" style="color: red;">このレビューを削除する</button>
    </form>

    <br>
    <a href="{{ route('reviews.index') }}">一覧に戻る</a>

</body>
</html>