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

Route::get('books/index',[BooksController::class,'index']);

Route::post('/book_manager/login',[UsersController::class,'login']);

Route::post('books/show',[BooksController::class,'store']);

Route::delete('books/index',[BooksController::class,'delete'])->name('delete.submit');
Route::get('/Books/index', [BooksController::class, 'index'])->name('books.index'); // 書籍一覧

Route::post('/book_manager/login',[EmployeesController::class,'login']);

Route::get('Employees/edit',[EmployeesController::class,'edit']); // ユーザ表示名更新画面
Route::post('Employees/update',[EmployeesController::class,'update']); // ユーザ表示名更新

// 社員管理画面（ロック解除リスト表示）
Route::get('/Employees/index', [EmployeesController::class, 'index']);
// ロック解除処理
Route::post('/Employees/unlock', [EmployeesController::class, 'unlock']);

Route::post('/book_manager/login',[EmployeesController::class,'login']);
Route::get('/books/{isbn}/reviews/edit',[ReviewsController::class,'edit'])->name('reviews.edit');
Route::post('/books/{isbn}/reviews',[ReviewsController::class,'store'])->name('reviews.store');
Route::get('reviews/{id}/erase',[ReviewsController::class,'delete'])->name('reviews.erase');
Route::post('reviews/{id}/delete',[ReviewsController::class,'delete'])->name('reviews.delete');
