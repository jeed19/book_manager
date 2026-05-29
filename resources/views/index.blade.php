<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ラクーン社 書籍管理システム</title>
</head>
<body>
    <h1>ラクーン社 書籍管理システム</h1>
    <form action="/book_manager/login" method="post">
        @csrf
        社員ID <input type="text" name="employee_id" id="employee_id" minlength="8" maxlength ="8" value="{{ old('employee_id')}}" required><br>
        パスワード <input type="password" name="password" id="password" minlength="8" maxlength ="32" value="{{ old('password')}}" required /><br>
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