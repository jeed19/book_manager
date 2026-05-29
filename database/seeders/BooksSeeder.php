<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ４人の社員登録、１人は経理部、他３人は社員
        DB::table('books')->insert([
            'isbn' => 9784297152437,
            'book_name' => 'かやのき先生のITパスポート教室〈令和08年〉',
            'author_name' => '栢木 厚',
            'created_at' => null,
            'updated_at' => null
        ]);
    }
}
