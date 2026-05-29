<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>書籍照会画面</title>
    <style>
        /* 新しいヘッダー用のスタイル（左右にボタンを配置） */
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        /* ボタンの簡易スタイル（お好みで調整してください） */
        .btn-delete { background-color: #dc3545; color: white; border: none; padding: 6px 12px; cursor: pointer; border-radius: 4px; }
        .btn-submit { background-color: #28a745; color: white; border: none; padding: 6px 12px; cursor: pointer; border-radius: 4px; }

        /* 左右2カラム分割の最低限のレイアウト */
        .screen-layout { display: flex; gap: 20px; padding: 20px; }
        .left-side { flex: 1; border: 1px solid #ccc; padding: 15px; }
        .right-side { flex: 1; display: flex; flex-direction: column; gap: 20px; }
        .right-top, .right-bottom { border: 1px solid #ccc; padding: 15px; }
        
        /* 星評価（ラジオボタン）の簡易スタイル */
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; font-size: 24px; }
        .star-rating input { display: none; }
        .star-rating label { cursor: pointer; color: #ccc; }
        .star-rating input:checked ~ label, .star-rating label:hover, .star-rating label:hover ~ label { color: #ffcc00; }
        
        .comment-area { width: 100%; margin: 10px 0; padding: 8px; box-sizing: border-box; }
    </style>
</head>
<body>

    <header class="header-actions">
        <form action="{{ route('delete.submit') }}" method="POST" onsubmit="return confirm('本当にこの書籍を削除しますか？');">
            <input type="hidden" name="isbn" value="{{$record->isbn}}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete">書籍削除</button>
        </form>

        <button type="submit" form="comment-form" class="btn-submit">投稿</button>
    </header>

    <main class="screen-layout">
        
        <section class="left-side">
            @yield('book_info_content') 
        </section>

        <div class="right-side">
            
            <section class="right-top">
                <h4>あなたの評価とコメント</h4>
                <form action="#" method="POST" id="comment-form">
                    @csrf
                    
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                    </div>

                    <textarea name="comment" class="comment-area" rows="4" placeholder="評価コメントを入力欄に記入してください"></textarea>
                    
                    <button type="submit">コメントを編集する</button>
                </form>
            </section>

            <section class="right-bottom">
                <h4>他のユーザーの評価・コメント</h4>
                @yield('other_comment_content')
            </section>
            
        </div>
    </main>

    {{ dd($record->isbn) }}
</body>
</html>