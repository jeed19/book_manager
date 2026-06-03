<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\models\Department;    

class ReviewsController extends Controller
{
    public function create(Request $req)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $isbn = $req->isbn;
        $book = Book::where('isbn', $isbn)->first();

        if(!$book) {
            return redirect()->action([BooksController::class,'index']);
        }

        $data = [
            'session_data' => $session_data,
            'book' => $book,
            'review' => null, 
        ];

        return view('reviews.create',$data);
    }

    public function store(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }


        $req->validate([
            'recommended_level' => 'required|integer|between:0,5',
            'title' => 'required|string|max:50',
            'comment' => 'required|string',
        ]);

        Review::updateOrCreate(

            [
                'employee_id' => $session_data['employee_id'],
                'isbn' => $isbn,
            ],

            [
                'recommended_level' => $req->recommended_level,
                'title' => $req->title,
                'comment' => $req->comment,
            ]
        );
        return redirect()->route('books.show', ['isbn' => $isbn])
                ->with('success', '投稿しました');

    }

    public function show(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $review = Review::where('isbn', $isbn)
                        ->where('employee_id', $session_data['employee_id'])
                        ->first();

        $book = Book::where('isbn', $isbn)->first();
        
        $sort = $req->input('sort', 'new');
        $limit = $req->input('limit', '3');

        $query = Review::with('employee')->where('isbn', $isbn);

        if ($sort === 'old') {
            $query->orderBy('created_at', 'asc'); 
        } elseif ($sort === 'rating_high') {
            $query->orderBy('recommended_level', 'desc')->orderBy('created_at', 'desc'); 
        } elseif ($sort === 'rating_low') {
            $query->orderBy('recommended_level', 'asc')->orderBy('created_at', 'desc'); 
        } else {
            $query->orderBy('created_at', 'desc'); 
        }

        if ($limit === 'all') {
            $total_count = clone $query;
            $count = $total_count->count();
            $perPage = $count > 0 ? $count : 1;
            $other_reviews = $query->paginate($perPage);
        } else {
            $other_reviews = $query->paginate((int)$limit);
        }

        $other_reviews->appends($req->all());

        // --- 【今回追加する集計処理】 ---
        // レビュー件数を取得
        $review_count = Review::where('isbn', $isbn)->count();

        // 平均評価を取得（四捨五入して小数点第1位まで丸める。レビューがない場合は 0.0）
        $review_avg = Review::where('isbn', $isbn)->avg('recommended_level');
        $review_avg = $review_avg ? round($review_avg, 1) : 0.0;
        // ---------------------------------

        $data = [
            'session_data' => $session_data,
            'reviews' => $review,
            'record' => $book,
            'other_reviews' => $other_reviews,
            // ビューへ追加した変数を渡す
            'review_count' => $review_count,
            'review_avg' => $review_avg,
        ];

        return view('books.show', $data);
    }

        public function showReviewsDetail(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $review = Review::where('isbn', $isbn)
                        ->where('employee_id', $session_data['employee_id'])
                        ->first();

        $book = Book::where('isbn', $isbn)->first();

        $other_reviews = Review::with('employee')
                            ->where('isbn', $isbn)
                            ->orderBy('created_at','desc')
                            ->get();

        $data = [
            'session_data'  => $session_data,
            'review'        => $review,
            'book'          => $book,
            'other_reviews' => $other_reviews,
        ];

        return view('reviews.show', $data);
    }

        public function index(Request $req)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $reviews = Review::with('book')
                        ->where('employee_id', $session_data['employee_id'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        $data = [
            'session_data' => $session_data,
            'reviews' => $reviews,
        ];

        return view('reviews.index',$data);

    }

    public function edit(Request $req,$isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }
        
        $target_employee_id = $session_data['employee_id'];

        $can_delete_review = false;
        if (isset($session_data['department_name'])) {
            $can_delete_review = \App\Models\Department::where('department_name', $session_data['department_name'])->value('can_delete_review');
        }

        if ($req->has('target_employee') && $can_delete_review) {
            $target_employee_id = $req->input('target_employee');
        }

        $review = Review::where('isbn', $isbn)
                        ->where('employee_id', $target_employee_id)
                        ->first();

                        if(!$review){
            return redirect()->back()->withErrors(['error' => 'レビューが存在しないか、権限がありません']);
        }

        $book = Book::where('isbn', $isbn)->first();

        $data = [
            'session_data' => $session_data,
            'book' => $book,
            'review' => $review,
        ];

        return view('reviews.edit',$data);
    }

    public function update(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $target_employee_id = $session_data['employee_id'];

        $can_delete_review = false;
        if (isset($session_data['department_name'])) {
            $can_delete_review = \App\Models\Department::where('department_name', $session_data['department_name'])->value('can_delete_review');
        }

        if ($req->has('target_employee') && $can_delete_review) {
            $target_employee_id = $req->input('target_employee');
        }

        $review = Review::where('isbn', $isbn)
                        ->where('employee_id', $target_employee_id)
                        ->first();

        if(!$review){
            return redirect()->back()->withErrors(['error' => 'レビューが存在しないか、権限がありません']);
        }

        $req->validate([
            'recommended_level' => 'required|integer|between:0,5',
            'title' => 'required|string|max:50',
            'comment' => 'required|string',
        ]);


        $review->recommended_level = $req->recommended_level;
        $review->title = $req->title;
        $review->comment = $req->comment;
        $review->save();


        return redirect()->route('reviews.show', ['isbn' => $review->isbn])
            ->with('success', 'レビューを更新しました');

    }


    public function delete(Request $req, $isbn)
    {
        $session_data = $req->session()->get('session_data');
        if(!$session_data){
            return redirect('/');
        }

        $target_employee_id = $session_data['employee_id'];

        $can_delete_review = false;
        if (isset($session_data['department_id'])) {
            $can_delete_review = \App\Models\Department::where('department_id', $session_data['department_id'])->value('can_delete_review');
        }

        if ($req->has('target_employee') && $can_delete_review) {
            $target_employee_id = $req->input('target_employee');
        }

        $review = Review::where('isbn', $isbn)
                        ->where('employee_id', $target_employee_id)
                        ->first();


        if($review){
            $isbn = $review->isbn;  
            $review->delete();
            return redirect()->route('books.show', ['isbn' => $isbn]);
        }

        return redirect()->back();
    }
}