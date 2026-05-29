<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;    

class ReviewsController extends Controller
{
    public function create(Request $req)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $isbn = $req->isbn;
        $book = Book::find($isbn);

        if(!$book) {
            return redirect()->action([BooksController::class,'index']);
        }

        $data = [
            'session_data' => $session_data,
            'book' => $book,
            'review' => null, //新規作成時は既存データが存在しないのでnull
        ];

        return view('Reviews.create',$data);
    }
    // レビューの保存、既存データあれば更新、なければ新規作成
    public function store(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        // バリデーション
        $req->validate([
            'isbn' => 'required',
            'recommended_level' => 'required|integer|between:0,5',
            'title' => 'required|string|max:50',
            'comment' => 'required|string',
        ]);

        Review::updateOrCreate(
            //検索条件で社員が書籍に対して書いたレビューが存在するか
            [
                'employee_id' => $session_data['employee_id'],
                'isbn' => $req->isbn,
            ],
            // 更新するデータ
            [
                'recommended_level' => $req->recommended_level,
                'title' => $req->title,
                'comment' => $req->comment,
            ]
        );
        return redirect()->action([BooksController::class, 'show'], ['isbn' => $isbn]);

    }
    //レビュー画面の単体表示
    public function show(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        //指定されたIDのレビューを取得
        $review = Review::find($isbn);

        //レビューが存在しない場合、書籍一覧に戻す
        if(!$review){
            return redirect()->action([BooksController::class,'index']);
        }

        //そのレビューが紐づく書籍情報を取得
        $book = Book::find($review->isbn);

        $data = [
            'session_data' => $session_data,
            'review' => $review,
            'book' => $book,
        ];

        return view('Reviews.show',$data);
    }

        public function index(Request $req)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        //全てのレビューを取得
        $reviews = Review::where('employee_id', $session_data['employee_id'])
                        ->orderBy('created_at', 'desc') //新しい順へ並び替え
                        ->get();

        $data = [
            'session_data' => $session_data,
            'reviews' => $reviews,
        ];

        return view('Reviews.index',$data);

    }
    //レビュー編集画面の表示
    public function edit(Request $req,$isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        //編集するレビューを取得
        $review = Review::find($isbn);

        //他人のレビューを編集しようとしていないかチェック
        if(!$review || $review->employee_id != $session_data['employee_id']){
            return redirect()->back()->withErrors(['error' => '権限がありません']);
        }

        //書籍情報を取得
        $book = Book::find($review->isbn);

        $data = [
            'session_data' => $session_data,
            'book' => $book,
            'review' => $review, //フォームに初期値を入れる為渡す
        ];

        return view('Reviews.edit',$data);
    }
    //レビュー更新処理
    //edit画面で直した内容を、実際にデータベースへ上書き保存する。
    public function update(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        //更新対象のレビューを取得
        $review = Review::find($isbn);

        //他人のレビューを更新していないかチェック
        if(!$review || $review->employee_id != $session_data['employee_id']){
            return redirect()->back()->withErrors(['error' => '権限がありません']);
        }

        $req->validate([
            'recommended_level' => 'required|integer|between:0,5',
            'title' => 'required|string|max:50',
            'comment' => 'required|string',
        ]);

        //データの上書き保存
        $review->recommended_level = $req->recommended_level;
        $review->title = $req->title;
        $review->comment = $req->comment;
        $review->save();

        //更新後、書籍の詳細画面へ戻す
        return redirect()->action([BooksController::class, 'show'], ['isbn' => $review->isbn])
            ->with('success', 'レビューを更新しました');

    }

    //感想コメント削除実行
    public function delete(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $review = Review::find($isbn);

        //他人のレビューを削除しようとしていないか確認する為のコード
        if($review && $review->employee_id == $session_data['employee_id']){
            $isbn = $review->isbn;  //リダイレクト用にisbnを控えておく
            $review->delete();
            return redirect()->action([BooksController::class, 'show'],['isbn' => $isbn]);
        }

        return redirect()->back();
    }
}