<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Book;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigInteger('isbn')->primary(); //書籍識別ID用
            $table->string('book_name',Book::MAX_BOOK_NAME); //書籍名用
            $table->string('author_name',Book::MAX_AUTHOR_NAME)->nullable(); //著者名
            $table->string('cover_image',Book::MAX_COVER_IMAGE)->nullable(); //書籍画像
            $table->string('publisher',Book::MAX_PUBLISHER)->nullable(); // 出版社
            $table->integer('publish_date')->nullable();     // 出版日
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
