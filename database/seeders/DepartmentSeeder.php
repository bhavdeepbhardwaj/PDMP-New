<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            [
                'department_code' => 'IT',
                'department_name' => 'Information Technology',
                'status' => 1,
            ],
            [
                'department_code' => 'ADMIN',
                'department_name' => 'Administration',
                'status' => 1,
            ],
            [
                'department_code' => 'OPS',
                'department_name' => 'Operations',
                'status' => 1,
            ],
            [
                'department_code' => 'ACC',
                'department_name' => 'Accounts',
                'status' => 1,
            ],
        ]);
    }
}
