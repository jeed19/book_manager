<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ４人の社員登録、１人は経理部、他３人は社員
        DB::table('employees')->insert([
            'employee_id' => 10000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 200, // 経理
            'employee_name' => '経理 太郎',
            'display_name' => 'ケリー'
        ]);
        DB::table('employees')->insert([
            'employee_id' => 14000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 400,
            'employee_name' => '総務',
            'display_name' => 'ソーリー'
        ]);
        DB::table('employees')->insert([
            'employee_id' => 16000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 400,
            'employee_name' => '人事',
            'display_name' => 'じぃじ'
        ]);
        DB::table('employees')->insert([
            'employee_id' => 19000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 400,
            'employee_name' => '開発',
            'display_name' => 'グラマー'
        ]);
    }
}
