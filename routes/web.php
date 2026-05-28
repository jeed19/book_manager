<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\ReviewsController;

Route::get('/', function () {
    return view('index');
});

Route::get('books/index',[BooksController::class,'index']);

Route::post('/book_manager/login',[UsersController::class,'login']);
Route::get('/books/{isbn}/reviews/edit',[ReviewsController::class,'edit'])->name('reviews.edit');
Route::post('/books/{isbn}/reviews',[ReviewsController::class,'store'])->name('reviews.store');
Route::get('reviews/{id}/erase',[ReviewsController::class,'delete'])->name('reviews.erase');
Route::post('reviews/{id}/delete',[ReviewsController::class,'delete'])->name('reviews.delete');