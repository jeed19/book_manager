@extends('layouts.base')
@push('page_styles')
    <style>

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
@endpush

@section('main')

    <main class="screen-layout">
        
        <section class="left-side">
            <!-- @yield('book_info_content')  -->
            <!-- 共通パーツの呼び出し。データを属性として渡す -->
            <x-book-card title="{{$record->book_name}}" 
                        author="{{$record->author_name}}" 
                        image-url="{{$record->cover_image}}" 
            />

            <form action="{{ route('delete.submit') }}" method="POST" onsubmit="return confirm('本当にこの書籍を削除しますか？');">
                <input type="hidden" name="isbn" value="{{$record->isbn}}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">書籍削除</button>
            </form>
        </section>

        <div class="right-side">
            
            <section class="right-top">
                <h4>あなたの評価とコメント</h4>
                <form action="{{ route('reviews.store', ['isbn' => $record->isbn]) }}" method="POST" id="comment-form">
                    @csrf
                    
                    <div class="star-rating">
                        <input type="radio" id="star5" name="recommended_level" value="5" {{ ($reviews && $reviews->recommended_level == 5) ? 'checked' : ''}}><label for="star5">★</label>
                        <input type="radio" id="star4" name="recommended_level" value="4" {{ ($reviews && $reviews->recommended_level == 4) ? 'checked' : ''}}><label for="star4">★</label>
                        <input type="radio" id="star3" name="recommended_level" value="3" {{ ($reviews && $reviews->recommended_level == 3) ? 'checked' : ''}}><label for="star3">★</label>
                        <input type="radio" id="star2" name="recommended_level" value="2" {{ ($reviews && $reviews->recommended_level == 2) ? 'checked' : ''}}><label for="star2">★</label>
                        <input type="radio" id="star1" name="recommended_level" value="1" {{ ($reviews && $reviews->recommended_level == 1) ? 'checked' : ''}}><label for="star1">★</label>
                    </div>

                    <input type="text" name='title' class="title-input" placeholder="タイトル（５０文字以内）" maxlength="50" value="{{ $reviews ? $reviews->title : '' }}" required>

                    <textarea name="comment" class="comment-area" rows="4" placeholder="評価コメントを入力欄に記入してください">{{ $reviews ? $reviews->comment : '' }}</textarea>
                    
                    <button type="submit">コメントを編集する</button>
                    <button type="submit" form="comment-form" class="btn-submit">投稿</button>
                </form>
            </section>

            <section class="right-bottom">
                <h4>他のユーザーの評価・コメント</h4>
                @yield('other_comment_content')
            </section>
            
        </div>
    </main>
@endsection