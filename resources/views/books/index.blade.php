@extends('layouts.base')
@section('main')
    <p>ようこそ、{{$session_data['display_name']}} さん！</p>

    <table style="border-collapse: collapse; width: 100%;">
        <tr><th>ISBN</th><th>タイトル</th><th>著者名</th><th>画像</th>
            @foreach($records as $record)
            <tr>
                <td>{{$record->isbn}}</td>
                <td>{{$record->book_name}}</td>
                <td>{{$record->author_name}}</td>
                <td>
                    <div class="book-image" style="flex-shrink: 0; width: 120px;">
                        <img src="{{ $record->cover_image }}" 
                        alt="" 
                        style= "display: inline-block; width: 100%; height: auto; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    </div>
                </td>
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
@endsection