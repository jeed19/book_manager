@extends('layouts.base')
@section('main')
@section('title', 'ユーザ情報変更')

    <main>
        @if($errors->any())
            <ul style="color: red;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="form-container">
            <form action="/employees/update" method="post">
                @csrf
                <h2>ユーザ情報の更新</h2>
                <input type="hidden" name="employee_id" value="{{ $record->employee_id }}">

                <p>
                    表示名（ニックネーム）変更前：<b>{{ $record->display_name }}</b><br>
                    表示名（ニックネーム）変更後：<input type="text" name="new_display_name" value="{{ old('new_display_name', $record->display_name) }}">
                </p>

                <p>
                    <br>
                    現在のパスワード（パスワードを変更する場合のみ入力）：<br>
                    <input type="password" name="current_password">
                </p>

                <p>
                    新しいパスワード（変更する場合のみ入力）：<br>
                    <input type="password" name="new_password" minlength="8" maxlength="32"><br>
                    新しいパスワード（確認用）：<br>
                    <input type="password" name="new_password_confirmation" minlength="8" maxlength="32">
                </p>

                <input type="submit" value="更新">
            </form>
        </div>
    </main>
@endsection