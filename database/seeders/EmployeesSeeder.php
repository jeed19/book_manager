<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ４人の社員登録、１人は経理部、他３人は社員
        DB::table('employees')->insert([
            'EMPLOYEE_ID' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => Hash::make('Ict202601'),
            'department_id' => 200,
            'joining_date' => '2026-05-26'
        ]);
        DB::table('employees')->insert([
            'EMPLOYEE_ID' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => Hash::make('Ict202601'),
            'department_id' => 400,
            'joining_date' => '2026-05-26'
        ]);
        DB::table('employees')->insert([
            'EMPLOYEE_ID' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => Hash::make('Ict202601'),
            'department_id' => 400,
            'joining_date' => '2026-05-26'
        ]);
        DB::table('employees')->insert([
            'EMPLOYEE_ID' => fake()->unique()->numberBetween(10000000,99999999),
            'password' => Hash::make('Ict202601'),
            'department_id' => 400,
            'joining_date' => '2026-05-26'
        ]);
    }
}
