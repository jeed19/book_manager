<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;

Route::get('/', function () {
    return view('testTop');
});

Route::get('books/index',[BooksController::class,'index']);