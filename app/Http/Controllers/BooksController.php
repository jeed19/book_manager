<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
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
        return view('Books.create');
    }

    public function store(Request $req){
        
        $book = new Book();
        $isbn =$req->isbn;

        // もしすでに登録されている場合フォームへ戻す
        $exists = Book::where('isbn', $isbn)->exists();
        if ($exists){
            return redirect()->route('books.create')->withErrors(['isbn' => 'この本は既に登録されています。']);
        }

        try {
        // OpenBDのAPIへリクエストを飛ばす
        $url = "https://api.openbd.jp/v1/get?isbn={$isbn}";

     // 2. プロキシを指定し、タイムアウト時間（10秒）を設定してGETリクエストを送信
        $response = Http::withOptions([
            'proxy'   => 'http://172.16.61.1:3128', // プロキシサーバー
            'timeout' => 10,                        // 応答を待つ最大秒数
        ])->get($url, [
            'isbn' => $isbn, // クエリパラメータとして自動で ?isbn=... に変換されます
        ]);


        } catch (ConnectionException $e) {
            // タイムアウトや接続エラー時の処理
            return view('Books.create')->with('error', '書籍データの取得に失敗しました。時間をおいて試してください。');
        }

        //返されたデータをphpの配列へデコード
        $item = $response->json();
        // dd($item); 

        //もしデータが空の場合フォームへ戻す
        if (empty($item)|| is_null($item[0])){
            return redirect()->route('books.create')
                    ->withErrors(['isbn' => 'ISBNが正しくない、もしくはデータが存在しません']);
        }

        //opneBDから届いた書籍データを登録処理
        //opneBDが提供している要約データ名が「summary」なのでそこからデータを抜き出す
        $summary = $item[0]['summary'];
        // dd($summary);

        // OpenBDで画像URLが存在しない場合、GoogleBookAPIから取得を試みる
        if(!$summary['cover']){
            // 1. APIキーの存在チェック（fail-fast）
            
            $apiKey = config('services.google.books_api_key');
            if (!empty($apiKey)) {
                // 検索キーワード（例: ISBN: 9784041026441）　
                $keyword = "isbn:{$isbn}";
                $url = 'https://www.googleapis.com/books/v1/volumes';

                // 2. APIリクエストの送信（クエリパラメータに 'key' を追加）
                    $response = Http::withOptions([
                        'proxy'   => 'http://172.16.61.1:3128', // プロキシサーバー
                        'timeout' => 10,                        // 応答を待つ最大秒数
                    ])->get($url, [
                        'q'          => $keyword,
                        'maxResults' => 1,
                        'key'        => $apiKey, // ここでAPIキーを渡す
                    ]);


                // 3. レスポンスの成否チェック
                if (!$response->failed()) {
                    $data = $response->json();

                    // 4. 該当データの存在チェック
                    if (isset($data['items']) || !count($data['items']) === 0) {
                        $volumeInfo = $data['items'][0]['volumeInfo'];
                        $imageUrl = null;

                        // 5. 書影URLの抽出とHTTPS変換
                        if (isset($volumeInfo['imageLinks'])) {
                            $imageUrl = $volumeInfo['imageLinks']['thumbnail'] ?? $volumeInfo['imageLinks']['smallThumbnail'] ?? null;
                            if ($imageUrl) {
                                // 混在コンテンツ（Mixed Content）対策として、必ずhttpsに置換
                                $imageUrl = str_replace('http://', 'https://', $imageUrl);
                                $summary['cover'] = $imageUrl;
                            }
                        }   
                    }
                }
            }
        }
        
        $book->isbn = (int)$summary['isbn'];
        $book->book_name = $summary['title'] ?? 'タイトル不明';
        $book->author_name = $summary['author'] ?? '著者不明';
        $book->cover_image = $summary['cover'] ;

        // booksテーブルにデータを保存するメソッドの実行
        $book->save();
        // 登録したデータを照会画面に渡し、表示する
        $data =[
            'session_data' => $req->session()->get('session_data',0),
            'record' =>  $book,
            'reviews' => Review::where('isbn', $isbn)->first()
        ];
        
        return view('Books.show',$data);
    }

    public function delete(Request $req){ 
        $isbn =$req->isbn;
        $book = Book::where('isbn', $isbn)->first();
        $book ->delete();
        return redirect()->action([BooksController::class,'index']);
    }


    public function show(Request $req){
        $isbn = $req->isbn;
        $data =[
            'session_data' => $req->session()->get('session_data',0),
            'record' =>  Book::where('isbn', $isbn)->first(),
            'reviews' => Review::where('isbn', $isbn)->first()
        ];
        return view('Books.show',$data);
    }


}
