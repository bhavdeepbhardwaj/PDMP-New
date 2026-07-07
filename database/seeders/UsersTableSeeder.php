<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'employee_code' => 'EMP001',
                'title' => 'Mr.',
                'first_name' => 'John',
                'middle_name' => null,
                'last_name' => 'Doe',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'username' => 'johndoe',
                'mobile_number' => '9876543210',
                'official_address' => 'New Delhi, India',
                'organization_id' => 1,
                'state_id' => 1,
                'state_board_id' => 1,
                'port_id' => 1,
                'role_id' => 1,
                'department_id' => 1,
                'port_type_id' => 1,
                'report_to_user_id' => null,
                'extra_module' => ['dashboard', 'reports'],
                'password' =>  bcrypt('12345678'),
                'status' => true,
                'force_password_change' => 0,
                'password_changed_at' => now(),
                'is_deleted' => 0,
            ],
        ];

            // Looping and Inserting Array's Users into User Table
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
