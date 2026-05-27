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
        DB::table('employees')->insert([
            'EMPLOYEE_ID'=> 10000000,
            'password' => 'Ict202601',
            'department_id' => 200,
            
        ]);
    }
}
