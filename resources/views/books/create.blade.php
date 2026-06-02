@extends('layouts.base')
@section('main')
@section('title', '書籍登録')

    <main>
        <div class="form-container">
            <h2>書籍登録</h2>
            <form action="{{ route('create.submit') }}" method="post">
                @csrf
                <div class="form-group">
                    <label>ISBN</label>
                    <input type="text" name="isbn" value="{{old('isbn')}}" required>

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
@endsection