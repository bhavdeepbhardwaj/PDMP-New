<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  $roles = [
        //     [
        //         'role_code' => 'SUPER_ADMIN',
        //         'role_name' => 'Super Admin',
        //         'status' => 1,
        //     ],
        //     [
        //         'role_code' => 'ADMIN',
        //         'role_name' => 'Admin',
        //         'status' => 1,
        //     ],
        //     [
        //         'role_code' => 'APPROVER',
        //         'role_name' => 'Approver',
        //         'status' => 1,
        //     ],
        //     [
        //         'role_code' => 'DATA_ENTRY',
        //         'role_name' => 'Data Entry Operator',
        //         'status' => 1,
        //     ],
        //     [
        //         'role_code' => 'VIEWER',
        //         'role_name' => 'Viewer',
        //         'status' => 1,
        //     ],
        // ];

        $roles = [

            [
                'role_code'        => 'SUPERADMIN',
                'role_name'        => 'Superadmin',
                'description'      => 'Super Administrator',
                'access_scope'     => 'ALL',
                'assignment_type'  => 'ALL',
                'status'           => true,
            ],

            [
                'role_code'        => 'MINISTRY_NODAL_OFFICER',
                'role_name'        => 'Ministry Nodal Officer',
                'description'      => 'Ministry Nodal Officer (Admin I)',
                'access_scope'     => 'ALL',
                'assignment_type'  => 'ALL',
                'status'           => true,
            ],

            [
                'role_code'        => 'STATE_MARITIME_BOARD_NODAL_OFFICER',
                'role_name'        => 'State Maritime Board Nodal Officer',
                'description'      => 'State Maritime Board Nodal Officer (Admin II)',
                'access_scope'     => 'STATE_BOARD',
                'assignment_type'  => 'MULTIPLE',
                'status'           => true,
            ],

            [
                'role_code'        => 'PORT_NODAL_OFFICER',
                'role_name'        => 'Port Nodal Officer',
                'description'      => 'Port Nodal Officer',
                'access_scope'     => 'PORT',
                'assignment_type'  => 'SINGLE',
                'status'           => true,
            ],

            [
                'role_code'        => 'PORT_MANAGER',
                'role_name'        => 'Port Manager',
                'description'      => 'Port Manager',
                'access_scope'     => 'PORT',
                'assignment_type'  => 'SINGLE',
                'status'           => true,
            ],

            [
                'role_code'        => 'DATA_ENTRY_OFFICER',
                'role_name'        => 'Data Entry Officer',
                'description'      => 'Data Entry Officer',
                'access_scope'     => 'PORT',
                'assignment_type'  => 'SINGLE',
                'status'           => true,
            ],

            [
                'role_code'        => 'NIC',
                'role_name'        => 'NIC',
                'description'      => 'NIC Support',
                'access_scope'     => 'ALL',
                'assignment_type'  => 'ALL',
                'status'           => true,
            ],

            [
                'role_code'        => 'OTHER',
                'role_name'        => 'Other',
                'description'      => 'Other',
                'access_scope'     => 'CUSTOM',
                'assignment_type'  => 'CUSTOM',
                'status'           => true,
            ],

        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                [
                    'role_code' => $role['role_code'],
                ],
                $role
            );
        }
    }
}
