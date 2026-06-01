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
    <header>
        <p>社内の書籍で管理している書籍を登録、管理を行います</p>
        <h1>書籍管理システム</h1>
    </header>

    @section('main')
    @show

</body>
</html>