<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '書籍管理システム')</title>
    {{-- 既存のCSS --}}
    <link rel="stylesheet" href="{{ asset('css/books/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    @stack('page_styles')
</head>
<body>
    <header class="site-header">
        <h1 class="header-title">
            <a href="{{ route('books.index') }}">書籍管理システム</a>
        </h1>
        
        <nav class="header-nav">
            <ul class="menu-list">
                @if(session('session_data')['can_register_book'])
                    <li><a href="/books/create" class="create-book-link">書籍登録</a></li>
                @endif
                
                <li><a href="/employees/edit">ユーザ表示名・パスワード変更</a></li>
            
                @if(session('session_data')['can_unlock'])
                    <li><a href="/employees/index" class="admin-link">社員情報管理</a></li>
                @endif
                
                {{-- 【追加】キーワード検索フォーム --}}
                <div class="search-container" style="margin-bottom: 15px;">
                    <form action="{{ route('books.index') }}" method="GET">
                        {{-- 並べ替え状態を引き継ぐためのhidden --}}
                        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at_desc') }}">
                        
                        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="書名・著者名・出版社名で検索" style="padding: 5px; width: 250px;">
                        <button type="submit" style="padding: 5px 10px;">検索</button>
                        
                        @if(request('keyword'))
                            <a href="{{ route('books.index') }}" style="margin-left: 10px; text-decoration: none; color: #666;">クリア</a>
                        @endif
                    </form>
                </div>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-link">
                        ログアウト
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        @section('main')
        @show
    </main>
</body>
</html>