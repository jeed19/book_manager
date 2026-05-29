<?php

use App\Http\Controllers\EmployeesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\ReviewsController;

Route::get('/', function () {
    return view('index');
});

Route::get('books/index',[BooksController::class,'index']); // 書籍一覧

Route::post('/book_manager/login',[EmployeesController::class,'login']);

Route::get('employees/edit',[EmployeesController::class,'edit']); // ユーザ表示名更新画面
Route::post('employees/edit',[EmployeesController::class,'edit']); // ユーザ表示名更新画面
Route::post('employees/update',[EmployeesController::class,'update']); // ユーザ表示名更新
Route::post('/book_manager/login',[EmployeesController::class,'login']);
Route::get('/books/{isbn}/reviews/edit',[ReviewsController::class,'edit'])->name('reviews.edit');
Route::post('/books/{isbn}/reviews',[ReviewsController::class,'store'])->name('reviews.store');
Route::get('reviews/{id}/erase',[ReviewsController::class,'delete'])->name('reviews.erase');
Route::post('reviews/{id}/delete',[ReviewsController::class,'delete'])->name('reviews.delete');
