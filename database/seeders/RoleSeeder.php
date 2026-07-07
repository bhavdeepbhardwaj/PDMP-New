<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'role_code' => 'SUPER_ADMIN',
                'role_name' => 'Super Admin',
                'status' => 1,
            ],
            [
                'role_code' => 'ADMIN',
                'role_name' => 'Admin',
                'status' => 1,
            ],
            [
                'role_code' => 'APPROVER',
                'role_name' => 'Approver',
                'status' => 1,
            ],
            [
                'role_code' => 'DATA_ENTRY',
                'role_name' => 'Data Entry Operator',
                'status' => 1,
            ],
            [
                'role_code' => 'VIEWER',
                'role_name' => 'Viewer',
                'status' => 1,
            ],
        ]);
    }
}
