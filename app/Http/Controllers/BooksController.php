<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Employee;
use App\Models\Review;



class BooksController extends Controller
{
    public function index(Request $req){
        $data = [
            'session_data' => $req->session()->get('session_data',0),
            'records' => Book::all()
        ];
        return view('Books.index',$data);
    }

    public function create(Request $req){
        return view('Books.create',$data);
    }

    public function store(Request $req){
        $book = new Book();

        // もしすでに登録されている場合フォームへ戻す
        if (Book::find($req->isbn)){
            return view('Books.create');
        }

        // OpenBDのAPIへリクエストを飛ばす
        $response = Http::get("https://api.openbd.jp/v1/get?isbn={$req->isbn}");

        //返されたデータをphpの配列へデコード
        $item = $response->json();

        //もしデータが空の場合フォームへ戻す
        if (empty($item)){
            return view('Books.create');
        }

        //opneBDから届いた書籍データを登録処理
        //opneBDが提供している要約データ名が「summary」なのでそこからデータを抜き出す
        $summary = $item['summary'];

        $book->isbn = (int)$summary['isbn'];
        $book->book_name = $summary['title'] ?? 'タイトル不明';
        $book->author_name = $summary['author'] ?? '著者不明';
        $book->cover_image = $summary['cover'] ?? 'https://placehold.jp/150x200.png';

        // booksテーブルにデータを保存するメソッドの実行
        $book->save();

        // 登録したデータを照会画面に渡し、表示する
        $data =[
            'session_data' => $req->session()->get('session_data',0),
            'record' =>  Book::find($req->isbn),
            'reviews' => Review::find($req->isbn)
        ];
        return view('Books.show',$data);
    }

    public function erase(Request $req){
        $isbn = $req->isbn;
        $data=[
            'record' => Book::find($isbn)
        ];
        return view('Books.delete',$data);
    }

    public function delete(Request $req){
        $book = Book::find($isbn);
        $book ->delete();
        $data = [
            // 'user' => Employees::find($req->employee_id),
            'records' => Book::all()
        ];
        return redirect()->action([BooksController::class,'index']);
    }


    public function show(Request $req){
        $data =[
            'session_data' => $req->session()->get('session_data',0),
            'isbn' => $req->isbn,
            'bookRecord' => Book::find($req->isbn),
            'reviews' => Review::find($req->isbn)
        ];
        return view('Books.show',$data);
    }


}
