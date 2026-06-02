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

        /* ★ 他の画面と統一したレビューカードの装飾 */
        .review-card {
            background: #ffffff;
            border-left: 5px solid #ffcc00;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        /* 項目名のラベル文字 */
        .label-text {
            font-size: 0.9em;
            color: #666;
            margin-right: 10px;
        }

        /* ★ 他の画面と統一したコメントボックスの装飾 */
        .comment-box {
            background-color: #f9f9f9; 
            padding: 15px; 
            border-radius: 4px;
            margin-top: 5px;
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
                    
                    <p style="margin-top: 15px;"><span class="label-text">タイトル:</span> <strong style="font-size: 1.1em;">{{ $r->title }}</strong></p>

                    <p style="margin-top: 5px;"><span class="label-text">おすすめ度:</span> 
                        <span style="color: #ffcc00; font-size: 20px; letter-spacing: 2px;">
                            {{ str_repeat('★', $r->recommended_level) }}{{ str_repeat('☆', 5 - $r->recommended_level) }}
                        </span>
                        <span style="font-size: 14px; color: #555; margin-left: 5px;">({{ $r->recommended_level }}/5)</span>
                    </p>
                    
                    <div class="comment-box">
                        {!! nl2br(e($r->comment)) !!}
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px; margin-top: 10px;">
                        <span>
                            <span class="label-text">投稿日:</span> 
                            <strong>{{ $r->updated_at ? $r->updated_at->format('Y/m/d H:i') . ' 編集' : ($r->created_at ? $r->created_at->format('Y/m/d H:i') . ' 投稿' : '未設定') }}</strong>
                        </span>
                        <a href="{{ route('books.show', ['isbn' => $r->isbn]) }}" class="btn-detail">この書籍の詳細掲示板を見る</a>
                    </div>
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