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
    <p><strong>タイトル:</strong> {{ $review->title }}</p>
    <p><strong>コメント:</strong> {{ $review->comment }}</p>
    
    <hr>
    
    <a href="{{ route('reviews.edit', ['isbn' => $book->isbn]) }}">このレビューを編集する</a>
    
    <br><br>

    <form action="{{ route('reviews.delete', ['isbn' => $book->isbn]) }}" method="POST">
        @csrf
        <button type="submit" style="color: red;">このレビューを削除する</button>
    </form>

    <br>
    
    <hr>
    <h2>みんなのレビュー一覧</h2>
    
    @if(isset($other_reviews) && count($other_reviews) > 0)
        @foreach($other_reviews as $other)
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <p><strong>投稿者:</strong> {{ $other->employee->display_name ?? '社員ID: ' . $other->employee_id }}</p>
                
                <p><strong>おすすめ度:</strong> 
                    <span style="color: #ffcc00; font-size: 18px;">
                        {{ str_repeat('★', $other->recommended_level) }}{{ str_repeat('☆', 5 - $other->recommended_level) }}
                    </span>
                </p>
                
                <p><strong>タイトル:</strong> {{ $other->title }}</p>
                <p><strong>コメント:</strong> {!! nl2br(e($other->comment)) !!}</p>
            </div>
        @endforeach
    @else
        <p>他のユーザーのレビューはまだありません。</p>
    @endif
    
    <br>
    <a href="{{ route('reviews.index') }}">一覧に戻る</a>

</body>
</html>