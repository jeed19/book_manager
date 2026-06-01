<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイレビュー一覧</title>
    <style>
        /* 画面全体の基本設定 */
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            padding: 20px;
        }

        /* 掲示板全体を包む枠組み */
        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            font-size: 24px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* 戻るボタンの装飾 */
        .btn-back {
            display: inline-block;
            background-color: #95a5a6;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #7f8c8d;
        }

        /* レビュー1つ1つを「カード」のように見せる魔法 */
        .review-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .review-card:hover {
            transform: translateY(-2px); /* マウスを乗せると少しだけフワッと浮く */
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .book-title {
            font-size: 18px;
            font-weight: bold;
            color: #2980b9;
            margin-bottom: 5px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }

        .isbn-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .rating-stars {
            color: #f39c12;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .comment-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #3498db;
            margin-bottom: 15px;
            font-size: 15px;
            line-height: 1.6;
        }

        /* 詳細へ飛ぶリンクボタンの装飾 */
        .btn-detail {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-detail:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>マイレビュー（一覧）</h1>

        <a href="{{ url('/books/index') }}" class="btn-back">⬅ 書籍一覧に戻る</a>

        @if(count($reviews) > 0)
            @foreach($reviews as $r)
                <div class="review-card">
                    <div class="book-title">
                        📚 {{ $r->book->book_name ?? 'タイトル不明' }}
                    </div>
                    <div class="isbn-text">
                        対象のISBN: {{ $r->isbn }}
                    </div>
                    
                    <div class="rating-stars">
                        おすすめ度: {{ str_repeat('★', $r->recommended_level) }}{{ str_repeat('☆', 5 - $r->recommended_level) }}
                        <span style="font-size: 14px; color: #555;">({{ $r->recommended_level }}/5)</span>
                    </div>
                    
                    <div class="comment-box">
                        {!! nl2br(e($r->comment)) !!}
                    </div>
                    
                    <a href="{{ route('books.show', ['isbn' => $r->isbn]) }}" class="btn-detail">この書籍の詳細掲示板を見る</a>
                </div>
            @endforeach
        @else
            <div class="review-card" style="text-align: center; color: #777;">
                <p>まだ投稿したレビューはありません。</p>
            </div>
        @endif

    </div>
</body>
</html>