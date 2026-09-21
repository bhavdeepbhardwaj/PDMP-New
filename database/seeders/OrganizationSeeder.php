<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'organization_code' => 'MOPSW',
                'organization_name' => 'Ministry of Ports, Shipping and Waterways',
                'description' => 'Government of India',
                'status' => 1,
            ]
        ];

        foreach ($organizations as $organization) {
            Organization::updateOrCreate(
                [
                    'organization_code' => $organization['organization_code'],
                ],
                $organization
            );
        }
    }
}
