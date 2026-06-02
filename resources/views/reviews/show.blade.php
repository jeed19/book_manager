<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レビュー詳細</title>
    <style>
        /* 画面全体の基本設定 */
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        
        /* 全体を包んで中央に寄せる箱 */
        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* 見出しのデザイン */
        h1, h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
            margin-top: 30px;
        }

        /* 自分のレビュー部分（白背景のカード風） */
        .my-review-card {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        /* 他の人のレビュー部分（左に黄色の線が入ったカード風） */
        .other-review-card {
            background: #ffffff;
            border-left: 5px solid #ffcc00;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* 共通のボタンデザイン */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-primary { background-color: #3498db; color: white; }
        .btn-primary:hover { background-color: #2980b9; }
        
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-danger:hover { background-color: #c0392b; }
        
        .btn-secondary { background-color: #95a5a6; color: white; }
        .btn-secondary:hover { background-color: #7f8c8d; }

        /* 文字の強調とレイアウト */
        .label-text {
            font-size: 0.9em;
            color: #666;
            margin-right: 10px;
        }
        .star-display {
            color: #ffcc00; 
            font-size: 20px;
            letter-spacing: 2px;
        }
        .comment-box {
            background-color: #f9f9f9; 
            padding: 15px; 
            border-radius: 4px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>レビュー詳細画面</h1>

        <div class="my-review-card">
            <p><span class="label-text">書籍名:</span> <strong style="font-size: 1.2em;">{{ $book->book_name }}</strong></p>
            <p><span class="label-text">書籍ISBN:</span> <strong>{{ $book->isbn }}</strong></p>

            @if($review)
                <p><span class="label-text">投稿日:</span> <strong>{{ $review->created_at ? $review->created_at->format('Y/m/d H:i') : '未設定' }}</strong></p>
                
                <p><span class="label-text">おすすめ度:</span> 
                    <span class="star-display">
                        {{ str_repeat('★', $review->recommended_level) }}{{ str_repeat('☆', 5 - $review->recommended_level) }}
                    </span>
                </p>
                <p><span class="label-text">タイトル:</span> <strong>{{ $review->title }}</strong></p>
                
                <div class="comment-box">
                    <span class="label-text" style="display: block; margin-bottom: 5px;">コメント:</span>
                    {!! nl2br(e($review->comment)) !!}
                </div>
                
                <hr style="border: 0; border-top: 1px dashed #ccc; margin: 20px 0;">
                
                <div style="display: flex; gap: 15px; align-items: center;">
                    <a href="{{ route('reviews.edit', ['isbn' => $book->isbn]) }}" class="btn btn-primary">このレビューを編集する</a>

                    <form action="{{ route('reviews.delete', ['isbn' => $book->isbn]) }}" method="POST" onsubmit="return confirm('本当にこのレビューを削除しますか？');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">このレビューを削除する</button>
                    </form>
                </div>
            @else
                <p style="color: #e74c3c; font-weight: bold; margin-top: 20px;">※あなたはまだこの書籍にレビューを投稿していません。</p>
            @endif
        </div>

        <h2>みんなのレビュー一覧</h2>
        
        @if(isset($other_reviews) && count($other_reviews) > 0)
            @foreach($other_reviews as $other)
                <div class="other-review-card">
                    <p style="margin-top: 0;"><span class="label-text">タイトル:</span> <strong style="font-size: 1.1em;">{{ $other->title }}</strong></p>
                    
                    <p><span class="label-text">おすすめ度:</span> 
                        <span class="star-display">
                            {{ str_repeat('★', $other->recommended_level) }}{{ str_repeat('☆', 5 - $other->recommended_level) }}
                        </span>
                    </p>
                    
                    <div class="comment-box" style="margin-bottom: 15px;">
                        {!! nl2br(e($other->comment)) !!}
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 20px; font-size: 0.9em; border-top: 1px solid #eee; padding-top: 10px;">
                        <span><span class="label-text">投稿者:</span> <strong>{{ $other->employee->display_name ?? '社員ID: ' . $other->employee_id }}</strong></span>
                        <span><span class="label-text">投稿日:</span> <strong>{{ $other->created_at ? $other->created_at->format('Y/m/d H:i') : '未設定' }}</strong></span>
                    </div>

                    @php
                        $has_delete_auth = false;
                        if(isset($session_data['department_id'])) {
                            $has_delete_auth = \App\Models\Department::where('department_id', $session_data['department_id'])->value('can_delete_review');
                        }
                    @endphp

                    @if($has_delete_auth)
                        <div style="display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ccc;">
                            <span style="font-size: 12px; color: #e74c3c; font-weight: bold;">※管理者専用:</span>
                            
                            <a href="{{ route('reviews.edit', ['isbn' => $book->isbn, 'target_employee' => $other->employee_id]) }}" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">編集</a>
                            
                            <form action="{{ route('reviews.delete', ['isbn' => $book->isbn, 'target_employee' => $other->employee_id]) }}" method="POST" onsubmit="return confirm('【管理者権限】本当にこのユーザーのレビューを削除しますか？');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 4px 10px; font-size: 12px;">削除</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p style="color: #666; background-color: #fff; padding: 20px; border-radius: 4px; text-align: center;">他のユーザーのレビューはまだありません。</p>
        @endif
        
        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 40px;">
            <a href="{{ route('books.index') }}" class="btn btn-secondary" style="background-color: #34495e;">書籍一覧に戻る</a>
            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">マイレビュー一覧に戻る</a>
        </div>
    </div>
</body>
</html>