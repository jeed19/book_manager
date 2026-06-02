@extends('layouts.base')

@push('page_styles')
    <link rel="stylesheet" href="{{ asset('css/books/index.css') }}">
@endpush

@section('title', '書籍一覧')

@section('main')
    <p>ようこそ、{{$session_data['display_name']}} さん！</p>

    {{-- 並べ替え用UI --}}
    <div class="sort-container" style="margin-bottom: 20px;">
        <form action="{{ route('books.index') }}" method="GET" id="sort-form">
            <label for="sort_by">並べ替え:</label>
            <select name="sort_by" id="sort_by" onchange="document.getElementById('sort-form').submit();">
                <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>登録日の新しい順</option>
                <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>登録日の古い順</option>
                <!-- <option value="review_count" {{ request('sort_by') == 'review_count' ? 'selected' : '' }}>レビュー数の多い順</option>
                <option value="review_rating" {{ request('sort_by') == 'review_rating' ? 'selected' : '' }}>レビュー評価の高い順</option> -->
            </select>
        </form>
    </div>

    <div class="book-grid">
        @foreach($records as $record)
        <div class="book-card">
            
            {{-- 1. 画像とタイトルを GET メソッドの正しいルートでリンク化 --}}
            <a href="{{ route('books.show', ['isbn' => $record->isbn]) }}" class="book-detail-link">
                <div class="image-container">
                    @if($record->cover_image)
                        {{-- 参考コードに合わせ、 asset('storage/') が不要なケース（直接パスが入っている場合）は調整してください --}}
                        <img src="{{ $record->cover_image }}" alt="{{ $record->book_name }}" class="book-image">
                    @else
                        <div class="no-image">NO IMAGE</div>
                    @endif
                </div>

                <div class="book-title">{{ $record->book_name }}</div>
            </a>
            
            <div class="book-author">{{ $record->author_name }}</div>

            {{-- 2. 詳細ボタンも同じく 正しいルートのリンク（aタグ）に変更 --}}
            <!-- <div class="detail-action">
                <a href="{{ route('books.show', ['isbn' => $record->isbn]) }}" class="btn-show-link">
                    <button type="button" class="btn-show">詳細</button>
                </a>
            </div> -->

        </div>
        @endforeach
    </div>

@endsection