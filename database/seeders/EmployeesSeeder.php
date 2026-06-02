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
            'display_name' => 'ケリー',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
        DB::table('employees')->insert([
            'employee_id' => 16000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 300,
            'employee_name' => '人事 太郎',
            'display_name' => 'じぃじ',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
        DB::table('employees')->insert([
            'employee_id' => 19000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 500,
            'employee_name' => '開発 太郎',
            'display_name' => 'グラマー',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
        DB::table('employees')->insert([
            'employee_id' => 20000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 200,
            'employee_name' => '月ノ 美酢',
            'display_name' => '鈴原るる推し',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
        DB::table('employees')->insert([
            'employee_id' => 40000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 200,
            'employee_name' => '篠河 栞子',
            'display_name' => 'ビブリア',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
        DB::table('employees')->insert([
            'employee_id' => 77777777,
            'password' => Hash::make('Ict202601'),
            'department_id' => 200,
            'employee_name' => '長門 侑希',
            'display_name' => 'YUKI.N',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
        DB::table('employees')->insert([
            'employee_id' => 90000000,
            'password' => Hash::make('Ict202601'),
            'department_id' => 900,
            'employee_name' => '栞葉 るる',
            'display_name' => 'るりドッグ',
            'login_failure_count' => 0,
            'locked_at' => null
        ]);
    }
}
