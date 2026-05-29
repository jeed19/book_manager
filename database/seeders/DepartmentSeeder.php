<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // DBファサードをインポート

class DepartmentSeeder extends Seeder
{
    /**
     * シードデータの実行
     */
    public function run(): void
    {
        // 既存のデータを一度綺麗にしてから投入（重複エラー防止）
        DB::table('departments')->truncate();

        // 部署データの定義
        $departments = [
            [
                'department_id' => '100',
                'department_name' => '営業部',
                'can_unlock' => false,        // ロック解除不可
                'can_register_book' => false, // 書籍登録不可
            ],
            [
                'department_id' => '200',
                'department_name' => '経理部',
                'can_unlock' => false,        // ロック解除不可
                'can_register_book' => true,  // 書籍登録可
            ],
            [
                'department_id' => '300',
                'department_name' => '人事部',
                'can_unlock' => false,        // ロック解除不可
                'can_register_book' => false,  // 書籍登録不可
            ],
            [
                'department_id' => '500',
                'department_name' => '開発部',
                'can_unlock' => false,        // ロック解除不可
                'can_register_book' => false,  // 書籍登録不可
            ],
            [
                'department_id' => '800',
                'department_name' => '情報システム部',
                'can_unlock' => true,         // ロック解除可
                'can_register_book' => false,  // 書籍登録不可
            ],
            [
                'department_id' => '900',
                'department_name' => '総務部',
                'can_unlock' => true,         // ロック解除可
                'can_register_book' => false, // 書籍登録不可
            ],
        ];

        // データを一括挿入
        DB::table('departments')->insert($departments);
    }
}