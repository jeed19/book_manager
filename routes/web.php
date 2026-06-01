<?php

use App\Http\Controllers\EmployeesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\ReviewsController;

Route::get('/', function () {
    return view('index');
});

Route::get('books/create', function () {
    return view('books/create');
})->name('books.create');

Route::post('/book_manager/login',[EmployeesController::class,'login']);
Route::get('books/index',[BooksController::class,'books.index']);

Route::post('/book_manager/login',[EmployeesController::class,'login']);

Route::post('/book_manager/books/show',[BooksController::class,'show'])->name('show.submit');

Route::post('books/store',[BooksController::class,'store'])->name('create.submit');

Route::delete('books/index',[BooksController::class,'delete'])->name('delete.submit');
Route::get('/books/index', [BooksController::class, 'index'])->name('books.index'); // 書籍一覧

Route::post('/book_manager/login',[EmployeesController::class,'login']);

Route::get('employees/edit',[EmployeesController::class,'edit']); // ユーザ表示名更新画面
Route::post('employees/update',[EmployeesController::class,'update']); // ユーザ表示名更新

// 社員管理画面（ロック解除リスト表示）
Route::get('/employees/index', [EmployeesController::class, 'index']);
// ロック解除処理
Route::post('/employees/unlock', [EmployeesController::class, 'unlock']);

// レビュー画面
Route::get('/book_manager/books/show/{isbn}', [App\Http\Controllers\ReviewsController::class, 'show'])->name('books.show');
Route::post('/reviews/store/{isbn}', [App\Http\Controllers\ReviewsController::class, 'store'])->name('reviews.store');
Route::get('/books/{isbn}/reviews/edit', [App\Http\Controllers\ReviewsController::class, 'edit'])->name('reviews.edit');
Route::get('/my-reviews', [App\Http\Controllers\ReviewsController::class, 'index'])->name('reviews.index');
Route::post('/books/{isbn}/reviews/delete', [App\Http\Controllers\ReviewsController::class, 'delete'])->name('reviews.delete');
Route::get('book_manager/books/{isbn}/show', [App\Http\Controllers\ReviewsController::class, 'show'])->name('reviews.show');
Route::get('book_manager/books/{isbn}/show', [BooksController::class, 'show'])->name('books.show');
// ログアウト
Route::post('/logout', [EmployeesController::class, 'logout'])->name('logout');
