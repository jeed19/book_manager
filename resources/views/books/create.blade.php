<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍登録</title>
    <link rel="stylesheet" href="{{ asset('css/books/create.css') }}">
</head>
<body>

    <header>
        <h1>書籍管理システム</h1>
    </header>

    <main>
        <div class="form-container">
            <h2>書籍登録</h2>
            <form action="{{ route('create.submit') }}" method="post">
                @csrf
                <div class="form-group">
                    <label>書籍名</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="書籍名を入力">

                    @error('title')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <div class="form-group">
                    <label>ISBN</label>
                    <input type="number" name="isbn" required>

                    @error('isbn')
                    <div class="error">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <input type="submit" value="書籍登録">
            </form>
        </div>
    </main>
</body>
</html>