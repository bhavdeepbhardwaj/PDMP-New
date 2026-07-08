<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_code' => 'CHD',
                'department_name' => 'Cargo Handling Division',
                'status' => 1,
            ],
            [
                'department_code' => 'TD',
                'department_name' => 'Traffic Department',
                'status' => 1,
            ],
            [
                'department_code' => 'PDD',
                'department_name' => 'Planning and Development Department',
                'status' => 1,
            ],
            [
                'department_code' => 'WSD',
                'department_name' => 'Warehousing and Storage Department',
                'status' => 1,
            ],
            [
                'department_code' => 'MD',
                'department_name' => 'Marine Department',
                'status' => 1,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                [
                    'department_code' => $department['department_code'],
                ],
                $department
            );
        }
    }
}
