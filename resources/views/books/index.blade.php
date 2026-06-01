@extends('layouts.base')
@section('main')
    <p>ようこそ、{{$session_data['display_name']}} さん！</p>

    <table>
        <tr><th>ISBN</th><th>タイトル</th><th>著者名</th><th>画像</th>
            @foreach($records as $record)
            <tr>
                <td>{{$record->isbn}}</td>
                <td>{{$record->book_name}}</td>
                <td>{{$record->author_name}}</td>
                <td>{{$record->cover_image}}</td>
                <td>
                        <a href="{{ route('books.show', ['isbn' => $record->isbn]) }}">
                            <button type="submit" style="cursor: pointer;">書籍照会</button>
                        </form>

                <td>
                </td>
            </tr>
            @endforeach
        </tr>  
    </table>

    <br>
    <ul>
        @if(session('session_data')['can_register_book'])
        <li><a href="/books/create">書籍作成</a></li>
        @endif
        <li><a href="/employees/edit">ユーザ表示名・パスワード変更</a></li>
    
        @if(session('session_data')['can_unlock'])
        <li><a href="/employees/index" style="color: blue; font-weight: bold;">【管理者用】アカウントロック解除画面</a></li>
        @endif
    </ul>
@endsection