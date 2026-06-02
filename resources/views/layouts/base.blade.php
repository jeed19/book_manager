<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '書籍管理システム')</title>
    {{-- 既存のCSS --}}
    <link rel="stylesheet" href="{{ asset('css/books/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        /* ボタンの簡易スタイル */
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

        /* 綺麗なカードデザインのCSS */
        .other-review-card {
            background: #ffffff;
            border-left: 5px solid #ffcc00;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
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
        .review-comment-box {
            background-color: #f9f9f9; 
            padding: 15px; 
            border-radius: 4px;
            margin-top: 5px;
        }
    </style>
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