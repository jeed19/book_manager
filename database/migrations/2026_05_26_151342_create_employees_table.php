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
            $table->integer('EMPLOYEE_ID')->primary(); //ログイン用のID
            $table->string('password',32);  //ログイン用のPW
            $table->integer('department_id'); //各役職識別用
            $table->dateTime('joining_date'); //入社日用
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
