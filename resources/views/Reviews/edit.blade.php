<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レビュー投稿</title>
</head>
<body>
    <h1>{{ $review ? 'レビューを編集する' : '新しくレビューを書く' }}</h1>

    <p>対象の書籍ISBN: {{ $book->isbn }}</p>

    <form action="{{ route('reviews.store', ['isbn' => $book->isbn]) }}" method="POST">
        @csrf

        <div>
            <label>おすすめ度 (0〜5):</label>
            <input type="number" name="recommended_level" min="0" max="5" value="{{ $review ? $review->recommended_level : 0 }}" required>
        </div>
        <br>
        <div>
            <label>タイトル:</label><br>
            <input type="text" name="title" maxlength="50" value="{{ $review ? $review->title : '' }}" required size="40">
        </div>
        <div>
            <label>感想コメント:</label><br>
            <textarea name="comment" rows="5" cols="40" required>{{ $review ? $review->comment : '' }}</textarea>
        </div>
        <br>
        <button type="submit">この内容で登録する！</button>
    </form>
    @if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <hr>
    <a href="{{ route('reviews.index') }}">マイレビュー一覧に戻る</a>
</body>
</html>