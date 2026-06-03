<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use App\Models\Book;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Review;



class BooksController extends Controller
{
    public function index(Request $req){
        
        // 1. クエリビルダの初期化
        $query = Book::query();

        // 2. あいまい検索処理の追加
        // キーワードが入力されている場合のみ実行
        if ($req->filled('keyword')) {
            $keyword = $req->input('keyword');
            
            $query->where(function($q) use ($keyword) {
                $q->where('book_name', 'like', "%{$keyword}%")
                  ->orWhere('author_name', 'like', "%{$keyword}%")
                  ->orWhere('publisher', 'like', "%{$keyword}%");
            });
        }

        // 3. リクエストから並べ替え条件を取得（デフォルトは新着順）
        $sortBy = $req->input('sort_by', 'created_at_desc');

        // 条件分岐による並べ替えの制御
        switch ($sortBy) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;

            case 'created_at_desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 4. 条件を適用してデータを取得
        $data = [
            'session_data' => $req->session()->get('session_data', 0),
            'records' => $query->get()
        ];

        return view('Books.index', $data);
    }

    public function create(Request $req){
        // 1. セッションチェック
        $session_data = $req->session()->get('session_data');
        if (!$session_data) {
            return redirect('/');
        }

        // 2. 書籍登録権限（can_register_book と仮定）のチェック
        $can_register_book = false;
        if (isset($session_data['department_name'])) {
            // 部署テーブルから、現在のユーザーの部署が登録権限を持っているか確認
            // ※ カラム名は実際のDB（例: can_register_book）に合わせて調整してください
            $can_register_book = Department::where('department_id', $session_data['department_id'])
                                           ->value('can_register_book');
        }

        // 権限がない場合は、書籍一覧へ戻してエラーメッセージを表示
        if (!$can_register_book) {
            return redirect()->route('books.index')->withErrors(['error' => '書籍登録の権限がありません。']);
        }

        return view('Books.create');
    }

    public function store(Request $req){
        // 1. セッションチェック
        $session_data = $req->session()->get('session_data');
        if (!$session_data) {
            return redirect('/');
        }

        // 2. 直接POST要請が来た場合のための防御（二重ガード）
        $can_register_book = false;
        if (isset($session_data['department_id'])) {
            $can_register_book = Department::where('department_id', $session_data['department_id'])
                                           ->value('can_register_book');
        }

        if (!$can_register_book) {
            return redirect()->route('books.index')->withErrors(['error' => '書籍登録の権限がありません。']);
        }
        
        $book = new Book();
        
        // 入力されたISBNを数字のみにクレンジング
        $isbn = $this->formatIsbn($req->isbn);

        // もしクレンジング結果が空、または正しい桁数（10桁か13桁）でない場合の簡易チェック
        // ※必要に応じてバリデーションを別途追加してください
        if (empty($isbn)) {
            return redirect()->route('books.create')->withErrors(['isbn' => '有効なISBN番号を入力してください。']);
        }

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
        
        // DBの各項目へデータを入れる

        $book->isbn = (int)$summary['isbn'];
        
        // タイトル
        $book->book_name = mb_substr(
            $summary['title'] ?? 'タイトル不明', 
            0, 
            Book::MAX_BOOK_NAME, 
            'UTF-8'
        );

        $book->author_name = isset($summary['author']) 
            ? mb_substr($summary['author'], 0, Book::MAX_AUTHOR_NAME, 'UTF-8') 
            : null;

        $book->publisher = isset($summary['publisher']) 
            ? mb_substr($summary['publisher'], 0, Book::MAX_PUBLISHER, 'UTF-8') 
            : null;

        $book->cover_image = isset($summary['cover']) 
            ? mb_substr($summary['cover'], 0, Book::MAX_COVER_IMAGE, 'UTF-8') 
            : null;

        // 三項演算子などで、空文字「''」や未定義の場合に確実に null になるようにする
        $pubdate = !empty($summary['pubdate']) ? $summary['pubdate'] : null;

        // (int) でキャストして保存する（null の場合は null のまま）
        $book->publish_date = is_null($pubdate) ? null : (int)$pubdate;


        // booksテーブルにデータを保存するメソッドの実行
        $book->save();
        // 【追加】新しく登録した本のISBNを使って、レビュー数と平均評価を都度計算する
        // (登録直後なので通常は0件・0.0になりますが、エラーを防ぐために必須です)
        $review_count = Review::where('isbn', $isbn)->count();
        $review_avg = Review::where('isbn', $isbn)->avg('recommended_level');
        $review_avg = $review_avg ? round($review_avg, 1) : 0.0;

        // 登録したデータを照会画面に渡し、表示する
        $data =[
            'session_data' => $req->session()->get('session_data', 0),
            'record'       => $book,
            'reviews'      => Review::where('isbn', $isbn)->first(),
            // 【重要】ここに2つの変数を追加してビューに渡す
            'review_count' => $review_count,
            'review_avg'   => $review_avg,
        ];
        
        return view('Books.show', $data);
    }

    public function delete(Request $req){ 
        $isbn =$req->isbn;
        $book = Book::where('isbn', $isbn)->first();
        $book ->delete();
        return redirect()->action([BooksController::class,'index']);
    }


    public function show(Request $req){
        $isbn = $req->isbn;

        // 【ここにも追加しておくと安全です】
        $review_count = Review::where('isbn', $isbn)->count();
        $review_avg = Review::where('isbn', $isbn)->avg('recommended_level');
        $review_avg = $review_avg ? round($review_avg, 1) : 0.0;

        $data =[
            'session_data' => $req->session()->get('session_data', 0),
            'record'       => Book::where('isbn', $isbn)->first(),
            'reviews'      => Review::where('isbn', $isbn)->first(),
            // 変数を追加
            'review_count' => $review_count,
            'review_avg'   => $review_avg,
        ];
        return view('Books.show', $data);
    }

    /**
     * ISBNの入力値を半角数字のみにフォーマットする
     * * @param string|null $isbn
     * @return string
     */
    private function formatIsbn(?string $isbn): string
    {
        if (is_null($isbn)) {
            return '';
        }

        // 1. 全角数字を半角数字に変換（「１２３」→「123」）
        $converted = mb_convert_kana($isbn, 'n', 'UTF-8');

        // 2. 数字以外の文字（ハイフン、スペースなど）をすべて除去
        $cleaned = preg_replace('/[^0-9]/', '', $converted);

        return $cleaned;
    }
}
