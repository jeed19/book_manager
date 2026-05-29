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
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('employee_id')->primary(); //役員識別ID用
            $table->string('password',128); //ハッシュ化されて保存される
            $table->integer('department_id'); //部署ID
            $table->string('employee_name',32); //本名。変更不可
            $table->string('display_name',32); //表示名
            $table->integer('login_failure_count'); //ログイン失敗回数。一定回数失敗するとログイン制限される
            $table->datetime('locked_at')->nullable();; //ログインロックされた日時。nullでなければロック中判定
            // $table->dateTime('joining_date'); //入社日用
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
