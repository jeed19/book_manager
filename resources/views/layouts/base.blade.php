<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍登録</title>
    <link rel="stylesheet" href="{{ asset('css/books/create.css') }}">
    @stack('page_styles')
</head>
<body>
    <!-- <header>
        <p>社内で保有する書籍の登録・管理業務を行います</p>
        <h1>書籍管理システム</h1>
    </header> -->

<header style="background-color: #080808; border-bottom: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); font-family: sans-serif; padding: 0 24px;">
    <div style="max-w: 1200px; margin: 0 auto; height: 64px; display: flex; justify-content: space-between; align-items: center;">
        
        <!-- ロゴ・タイトル -->
        <div>
            <a href="{{ route('books.index') }}" style="font-size: 18px; font-weight: 700; color: #f9fbfd; text-decoration: none; tracking-wide: 0.05em;">
                書籍管理システム
            </a>
        </div>

        <!-- メニューリンク -->
        <nav style="display: flex; align-items: center; gap: 16px;">

            <!-- ユーザー名表示エリア -->
                @if(session('session_data')['can_register_book'])
                    <!-- 【管理者用】王冠（クラウン）アイコン ＋ ゴールド系の文字色 -->
                    <div style="display: flex; align-items: center; gap: 6px; color: #e5fb24; font-size: 14px; font-weight: 700; margin-right: 8px;" title="管理者アカウント">
                        <svg xmlns="http://w3.org" style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 16 16">
                            <path d="M14 12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a.5.5 0 0 1 .74-.445L6 5.4l2.26-1.808a.5.5 0 0 1 .596 0L11 5.4l3.26-2.345a.5.5 0 0 1 .74.445zM0 13a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V2a1 1 0 0 0-1.555-.832L11 3.55 8.278.384a1 1 0 0 0-1.556 0L4 3.55 1.555 1.168A1 1 0 0 0 0 2z"/>
                        </svg>
                        <span>[管理者] {{$session_data['display_name']}} さん</span>
                    </div>
                @else
                    <!-- 【一般ユーザー用】通常の人型アイコン ＋ 落ち着いた薄グレーの文字色 -->
                    <div style="display: flex; align-items: center; gap: 6px; color: #f2f3f5; font-size: 14px; font-weight: 500; margin-right: 8px;">
                        <svg xmlns="http://w3.org" style="width: 16px; height: 16px; fill: currentColor;" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                        <span>{{$session_data['display_name']}} さん</span>
                    </div>
                @endif

            @if(session('session_data')['can_register_book'])
            <a href="/books/create" style="display: inline-block; padding: 8px 16px; background-color: #2563eb; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
                書籍登録
            </a>
            @endif

        <a href="/employees/edit" 
        style="display: inline-block; background-color: #22c55e; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; padding: 8px 16px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;" 
        onmouseover="this.style.backgroundColor='#16a34a';" 
        onmouseout="this.style.backgroundColor='#22c55e';">
            ユーザ編集
        </a>

            @if(session('session_data')['can_unlock'])
            <a href="/employees/index" style="display: flex; align-items: center; gap: 6px; background-color: #fef2f2; color: #991b1b; border: 1px solid #fee2e2; border-radius: 6px; padding: 6px 12px; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fee2e2'" onmouseout="this.style.backgroundColor='#fef2f2'">
                <span style="width: 6px; height: 6px; background-color: #dc2626; border-radius: 50%;"></span>
                アカウントロック解除
            </a>
            @endif
        </nav>

    </div>
</header>

    @section('main')
    @show

</body>
</html>