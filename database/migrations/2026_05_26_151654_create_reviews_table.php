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
            $table->integer('EMPLOYEE_ID',8); //部署ID
            $table->integer('ISBN',13); //書籍識別番号
            $table->string('comment',1000); //書籍感想コメント用
            $table->integer('recommended_level',1); //書籍おすすめ度用
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
