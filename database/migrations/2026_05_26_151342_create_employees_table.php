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
            $table->string('password',128); 
            $table->integer('department_id'); //各役員名用
            $table->string('employee_name',32); 
            $table->string('display_name',32); 
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
