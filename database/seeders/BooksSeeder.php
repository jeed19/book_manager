<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 最初に書籍を３冊ISBNを配列に用意する
        $isbns = [
            9784297152437, //かやのき先生のITパスポート
            9784297152451, //かやのき先生の基本情報技術者試験
            9784297142674, //かやのき先生の情報Iプログラミング教室
        ];

        // 用意したISBNを,で繋ぎ、OpenBDに一発でリクエストをできるように
        $isbnString = implode(',', $isbns);

        // OpenBDのAPIへリクエストを飛ばす
        $response = Http::get("https://api.openbd.jp/v1/get?isbn={$isbnString}");

        //返されたデータをphpの配列へデコード
        $items = $response->json();

        //もしデータが空の場合処理を終了させる
        if (empty($items)){
            return;
        }

        //opneBDから届いた書籍データを１冊ずつループ処理
        foreach($items as $item){
            //もしデータがなかったらスキップ
            if(is_null($item)){
                continue;
            }

            //opneBDが提供している要約データ名が「summary」なのでそこからデータを抜き出す
            $summary = $item['summary'];

            DB::table('books')->insert([
                'ISBN' =>(int)$summary['isbn'],
                'book_name' => $summary['title'] ?? 'タイトル不明',
                'author_name' => $summary['author'] ?? '著者不明',
                //表示画像のURLを保存(画像のない場合ダミー画像URLを代入)
                'cover_image' => !empty($summary['cover']) ? $summary['cover'] : 'https://placehold.jp/150x200.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
