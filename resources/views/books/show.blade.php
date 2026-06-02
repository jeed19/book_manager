@extends('layouts.base')
@push('page_styles')
    <link rel="stylesheet" href="{{ asset('css/books/show.css') }}">
@endpush

@section('main')

    <main class="screen-layout">
        
        <section class="left-side">
            <!-- @yield('book_info_content')  -->
            <!-- 共通パーツの呼び出し。データを属性として渡す -->
            <x-book-card title="{{$record->book_name}}" 
                        author="{{$record->author_name}}" 
                        image-url="{!! htmlspecialchars_decode($record->cover_image) !!}" 
                        publisher="{{$record->publisher}}" 
                        publish_date="{{$record->publish_date}}" 
                        isbn="{{$record->isbn}}" 
            />

            @if(session('session_data')['can_register_book'])
            <form action="{{ route('delete.submit') }}" method="POST" onsubmit="return confirm('本当にこの書籍を削除しますか？');">
                <input type="hidden" name="isbn" value="{{$record->isbn}}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">書籍削除</button>
            </form>
            @endif
        <div style="border-top: 2px dashed #ccc; padding-top: 20px;">
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

                    <input type="text" name='title' class="title-input" placeholder="タイトル（５０文字以内）" maxlength="50" value="{{ $reviews ? $reviews->title : '' }}" required style="width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box;">

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
                    </div>
                </form>
            </div>
        </section>

        <div class="right-side">
            <section class="right-bottom" style="height: 100%;">
                <h4>他のユーザーの評価・コメント</h4>

                <form action="{{ route('books.show', ['isbn' => $record->isbn]) }}" method="GET" style="margin-bottom: 15px; display: flex; gap: 10px; align-items: center; justify-content: flex-end;">
                    <span style="font-size: 14px; color: #666;">並び替え:</span>
                    <select name="sort" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                        <option value="new" {{ request('sort', 'new') == 'new' ? 'selected' : '' }}>新しい順</option>
                        <option value="old" {{ request('sort') == 'old' ? 'selected' : '' }}>古い順</option>
                        <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>おすすめ度が高い順</option>
                        <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>おすすめ度が低い順</option>
                    </select>
                    
                    <span style="font-size: 14px; color: #666; margin-left: 10px;">表示件数:</span>
                    <select name="limit" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                        <option value="3" {{ request('limit', '3') == '3' ? 'selected' : '' }}>3件まで</option>
                        <option value="5" {{ request('limit') == '5' ? 'selected' : '' }}>5件まで</option>
                        <option value="all" {{ request('limit') == 'all' ? 'selected' : '' }}>すべて</option>
                    </select>
                </form>

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
                                <span><span class="label-text">投稿者:</span> <strong>{{ $other->employee->display_name ?? 'ニックネーム:' . ($other->employee_name ?? '匿名') }}</strong></span>
                                <span><span class="label-text">投稿日:</span> <strong>{{ $other->updated_at ? $other->updated_at->format('Y/m/d H:i') : $other->created_at->format('Y/m/d H:i') . ' 投稿' }}</strong></span>
                            </div>

                            @php
                                $has_delete_auth = false;
                                if(isset($session_data['department_name'])) {
                                    $has_delete_auth = \App\Models\Department::where('department_name', $session_data['department_name'])->value('can_delete_review');
                                }
                            @endphp

                            @if($has_delete_auth)
                                <div style="display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ccc;">
                                    <span style="font-size: 12px; color: #e74c3c; font-weight: bold;">※管理者専用:</span>
                                    
                                    <a href="{{ route('reviews.edit', ['isbn' => $record->isbn, 'target_employee' => $other->employee_id]) }}" style="background-color: #007bff; color: white; padding: 4px 10px; font-size: 12px; border-radius: 4px; text-decoration: none;">編集</a>
                                    
                                    <form action="{{ route('reviews.delete', ['isbn' => $record->isbn, 'target_employee' => $other->employee_id]) }}" method="POST" onsubmit="return confirm('【管理者権限】本当にこのユーザーのレビューを削除しますか？');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 4px 10px; font-size: 12px; border-radius: 4px; cursor: pointer;">削除</button>
                                    </form>
                                </div>
                            @endif
                            </div>
                    @endforeach

                    @if(method_exists($other_reviews, 'links'))
                        <div style="margin-top: 20px; display: flex; justify-content: center;">
                            <style>svg.w-5.h-5 { width: 20px; height: 20px; }</style>
                            {{ $other_reviews->links() }}
                        </div>
                    @endif

                @else
                    <p>まだ他のユーザーのレビューはありません。</p>
                @endif

                <div style="text-align: right; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 15px;">
                    <a href="{{ route('reviews.index') }}" class="btn-link" style="background-color: #6c757d;">
                        マイコメント一覧へ
                    </a>
                    <a href="{{ route('reviews.show', ['isbn' => $record->isbn]) }}" class="btn-link">
                        全てのレビュー詳細画面へ
                    </a>
                </div>
            </section>
        </div>
            </section>
        </div>
    </main>
@endsection
