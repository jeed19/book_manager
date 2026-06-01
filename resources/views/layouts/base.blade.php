<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '書籍管理システム')</title>
    {{-- 既存のCSS --}}
    <link rel="stylesheet" href="{{ asset('css/books/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
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
                    <li><a href="/employees/index" class="admin-link">【管理者用】アカウントロック解除画面</a></li>
                @endif
                
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