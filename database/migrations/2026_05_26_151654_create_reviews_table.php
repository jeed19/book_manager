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
        Schema::create('reviews', function (Blueprint $table) {
            $table->integer('EMPLOYEE_ID'); //部署ID
            $table->bigInteger('ISBN'); //書籍識別番号
            $table->string('title',50)->nullable();; //書籍タイトル用
            $table->string('comment',1000)->nullable();; //書籍感想コメント用
            $table->integer('recommended_level')->nullable();; //書籍おすすめ度用
            $table->timestamps();

            $table->primary(['EMPLOYEE_ID','ISBN']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
