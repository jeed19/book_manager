<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigInteger('isbn')->primary(); //書籍識別ID用
            $table->string('book_name',100); //書籍名用
            $table->string('author_name',100); //著者名
            $table->string('cover_image',100); //書籍画像
            $table->timestamps(); //作成日時用
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
