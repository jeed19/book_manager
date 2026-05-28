<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ４人の社員登録、１人は経理部、他３人は社員
        DB::table('employees')->insert([
            'employee_id' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => 'Ict202601',
            'department_id' => 200,
            'employee_name' => '伊藤',
            'display_name' => '伊藤'
        ]);
        DB::table('employees')->insert([
            'employee_id' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => 'Ict202601',
            'department_id' => 400,
            'employee_name' => '高城',
            'display_name' => '高城'
        ]);
        DB::table('employees')->insert([
            'employee_id' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => 'Ict202601',
            'department_id' => 400,
            'employee_name' => '寺岡',
            'display_name' => '寺岡'
        ]);
        DB::table('employees')->insert([
            'employee_id' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => 'Ict202601',
            'department_id' => 400,
            'employee_name' => '日根野',
            'display_name' => '日根野'
        ]);
    }
}
