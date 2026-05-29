<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/books/show" method="post">
        @csrf
        ISBN:<input type="number" name="isbn"  required>
        <input type="submit" value="書籍登録">
    </form>

    @error('isbn')
        <span style="color: red;">{{ $message }}</span>
    @enderror


</body>
</html>