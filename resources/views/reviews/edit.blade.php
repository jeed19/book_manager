<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レビュー投稿</title>
    <style>
        /* ① 全体の背景や文字の形を整える */
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            padding: 20px;
        }
        
        /* ② フォーム全体を包む白い枠組み（受付カウンター） */
        .form-container {
            background-color: #fff;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 24px;
            color: #2c3e50;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .book-info {
            background-color: #e8f4f8;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #2980b9;
        }

        /* ③ 入力欄の間の余白を整える */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* ④ 記入欄を綺麗にする */
        input[type="number"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        
        input:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        /* ⑤ 送信（青）ボタンの装飾 */
        .btn-submit {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 12px 20px;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #2980b9;
        }

        /* ⑥ ★追加：削除（赤）ボタンの装飾 */
        .btn-delete {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 12px 20px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px; /* 青いボタンとの間に少し隙間を空ける */
            transition: background-color 0.3s;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }

        /* 戻るリンクの装飾 */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        
        <h1>{{ $review ? 'レビューを編集する' : '新しくレビューを書く' }}</h1>

        <div class="book-info">
            書籍: {{ $book->book_name ?? 'タイトル不明' }} <br>ISBN: {{ $book->isbn }}
        </div>

        <form action="{{ route('reviews.store', ['isbn' => $book->isbn]) }}" method="POST" onsubmit="return confirm('{{ $review ? 'この内容で更新してもよろしいですか？' : 'この内容で投稿してもよろしいですか？' }}');">
            @csrf

            <div class="form-group">
                <label>おすすめ度 (0〜5):</label>
                <input type="number" name="recommended_level" min="0" max="5" value="{{ $review ? $review->recommended_level : 0 }}" required>
            </div>
            
            <div class="form-group">
                <label>タイトル:</label>
                <input type="text" name="title" maxlength="50" value="{{ $review ? $review->title : '' }}" required placeholder="例: とても参考になりました">
            </div>
            
            <div class="form-group">
                <label>感想コメント:</label>
                <textarea name="comment" rows="5" required placeholder="ここに感想を入力してください">{{ $review ? $review->comment : '' }}</textarea>
            </div>
            
            <button type="submit" class="btn-submit">
                {{ $review ? 'この内容で更新する' : 'レビューを投稿する' }}
            </button>
        </form>

        @if($review)
            <form action="{{ route('reviews.delete', ['isbn' => $book->isbn]) }}" method="POST" onsubmit="return confirm('本当にこのレビューを削除しますか？消したデータは元に戻せません。');">
                @csrf
                <button type="submit" class="btn-delete">このレビューを削除する</button>
            </form>
        @endif

        @if ($errors->any())
        <div style="color: #c0392b; background-color: #fdecec; border: 1px solid #e74c3c; padding: 10px; border-radius: 4px; margin-top: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <a href="{{ route('reviews.index') }}" class="back-link">マイレビュー一覧に戻る</a>
    </div>
</body>
</html>