<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;

Route::get('/', function () {
    return view('index');
});

Route::get('books/create', function () {
    return view('books/create');
})->name('books.create');

Route::get('books/index',[BooksController::class,'index']);

Route::post('/book_manager/login',[UsersController::class,'login']);

Route::post('books/show',[BooksController::class,'store']);

Route::delete('books/index',[BooksController::class,'delete'])->name('delete.submit');