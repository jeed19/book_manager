<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションの実行（テーブル作成）
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            // 3桁の固定長文字列として部署IDを定義し、主キー（Primary Key）に設定
            $table->integer('department_id')->primary();
            // 部署名（50文字制限）
            $table->string('department_name', 50);
            // 書籍登録権限フラグ（デフォルトは 0:権限なし）
            $table->boolean('can_register_book')->default(false);
            // ロック解除権限フラグ（デフォルトは 0:権限なし）
            $table->boolean('can_unlock')->default(false);
            
            // ※今回はモデル側で $timestamps = false を指定するため、
            // created_at, updated_at が不要であれば $table->timestamps(); は記述しません
        });
    }

    /**
     * マイグレーションの取り消し（テーブル削除）
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
