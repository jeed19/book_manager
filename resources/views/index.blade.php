<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('company.name') }} 書籍管理システム</title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
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