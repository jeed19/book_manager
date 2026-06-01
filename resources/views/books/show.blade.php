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

        .btn-link {
            display: inline-block;
            background-color: #17a2b8;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-link:hover {
            background-color: #138496;
        }
    </style>
@endpush

@section('main')

    <main class="screen-layout">
        
        <section class="left-side">
            <!-- @yield('book_info_content')  -->
            <!-- 共通パーツの呼び出し。データを属性として渡す -->
            <x-book-card title="{{$record->book_name}}" 
                        author="{{$record->author_name}}" 
                        image-url="{!! htmlspecialchars_decode($record->cover_image) !!}" 
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

                        <input type="radio" id="star0" name="recommended_level" value="0" {{ ($reviews && $reviews->recommended_level == 0) || !$reviews ? 'checked' : ''}}>
                        <label for="star0" style="font-size: 14px; color: #999; margin-left: 15px; padding-top: 5px;">評価なし:</lavel>
                    </div>

                    <input type="text" name='title' class="title-input" placeholder="タイトル（５０文字以内）" maxlength="50" value="{{ $reviews ? $reviews->title : '' }}" required>

                    <textarea name="comment" class="comment-area" rows="4" placeholder="評価コメントを入力欄に記入してください">{{ $reviews ? $reviews->comment : '' }}</textarea>
                    
                    <div style="text-align: right; margin-top: 10px;">
                        @if($reviews)
                            <a href="{{ route('reviews.edit', ['isbn' => $record->isbn]) }}" class="btn-submit" style="display: inline-block; text-align: center; text-decoration: none; box-sizing: border-box; background-color: #007bff;">
                                編集画面へ移動する
                            </a>
                        @else
                            <button type="submit" class="btn-submit" onclick="return confirm('この内容で投稿してもよろしいですか？');">
                                投稿する
                            </button>
                        @endif
                </form>
            </section>

            <section class="right-bottom">
                <h4>他のユーザーの評価・コメント</h4>
                @if(isset($other_reviews) && count($other_reviews) > 0)
                    @foreach($other_reviews as $other)
                        <div class="other-reviewsitem">
                            
                            <div class="other-reviewstitle" style="font-size: 1.2em; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                {{ $other->title }}
                            </div>
                            
                            <div class="other-reviewsstars" style="margin-top: 5px;">
                                {{ str_repeat('★',$other->recommended_level) }}{{ str_repeat('☆', 5 - $other->recommended_level) }}
                            </div>
                            
                            <div class="other-reviewscomment" style="margin-top: 10px;">
                                {!! nl2br(e($other->comment)) !!}
                            </div>
                            
                            <div class="other-reviewsname" style="margin-top: 15px; font-size: 0.9em; color: #555;">
                                投稿者: {{ $other->employee->display_name ?? 'ニックネーム:' . ($other->employee_name ?? '匿名') }}
                            </div>
                            
                            <div class="other-reviewsdate" style="font-size: 0.8em; color: #999; text-align: right;">
                                {{ $other->updated_at ? $other->updated_at->format('Y/m/d H:i') . ' 編集' : $other->created_at->format('Y/m/d H:i') . ' 投稿' }}
                            </div>
                            
                        </div>
                    @endforeach
                @else
                    <p>まだ他のユーザーのレビューはありません。</p>
                @endif

                <div style="text-align: right; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 15px;">
                    <a href="{{ route('reviews.show', ['isbn' => $record->isbn]) }}" class="btn-link">
                        全てのレビュー詳細画面へ
                    </a>
                </div>
            </section>
        </div>
    </main>
@endsection