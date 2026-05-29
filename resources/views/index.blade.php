<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ラクーン社 書籍管理システム</title>
        <style>
        /* 画面全体を中央揃えにするための設定 */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center; /* 左右の中央揃え */
            min-height: 100vh;
            font-family: sans-serif;
        }

        /* 会社名（タイトル）のスタイル */
        .company-title {
            font-size: 32px;       /* 文字を大きく */
            font-weight: bold;     /* 太字に */
            margin-top: 80px;      /* 上からの位置調整 */
            margin-bottom: 20px;   /* フォームとの間隔 */
            color: #333;
        }

        /* フォーム全体のコンテナ */
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;   /* 中身（子要素）を中央に揃える */
            gap: 12px;             /* 入力欄やボタンのすき間 */
            width: 300px;          /* 横幅を固定して中央にまとめる */
        }

        /* ラベルのスタイル */
        .form-container label {
            font-size: 14px;
            color: #555;
            align-self: center;    /* ラベル文字も中央揃え */
        }

        /* 入力欄のスタイル */
        .form-container input[type="text"], 
        .form-container input[type="password"] {
            width: 100%;           /* コンテナの幅（300px）いっぱいに広げる */
            padding: 10px;         /* 内側の余白を少し広げて押しやすく */
            box-sizing: border-box;/* 幅の計算を狂わせない設定 */
            border: 1px solid #ccc;
            border-radius: 4px;    /* 角を少し丸く */
            text-align: center;    /* 入力する文字も中央揃えにする場合（不要なら消してください） */
        }

        /* ログインボタンのスタイル */
        .form-container input[type="submit"] {
            width: 100%;           /* ボタンも入力欄と同じ幅に揃える */
            padding: 12px;
            background-color: #333;/* ボタンの色（黒〜濃いグレー） */
            color: #fff;           /* 文字を白に */
            border: none;
            border-radius: 4px;
            cursor: pointer;       /* マウスを乗せたときに指マークにする */
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* ボタンにマウスを乗せたときの反応 */
        .form-container input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="company-title">{{ config('company.name') }}　書籍管理システム</div>
    <form action="/book_manager/login" method="post" class="form-container">
        @csrf
        <label for="employee_id">社員ID</label> 
        <input type="text" name="employee_id" id="employee_id" minlength="8" maxlength ="8" value="{{ old('employee_id')}}" required placeholder="ID"><br>
        <label for="password">パスワード</label> 
        <input type="password" name="password" id="password" minlength="8" maxlength ="32" value="{{ old('password')}}" required placeholder="パスワード" /><br>
        <input type="submit" value="ログイン">
    </form>
    <br>
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>