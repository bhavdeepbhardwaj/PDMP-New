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
            ],
            [
                'organization_code' => 'AP',
                'organization_name' => 'Directorate Of Port Govt Of AP',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'AN',
                'organization_name' => 'Port Management Board A N',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'GOA',
                'organization_name' => 'Capt of Ports GOVT OF GOA',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'GMB',
                'organization_name' => 'Gujarat Maritime Board',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'KAR',
                'organization_name' => 'Directorate Of Ports Karnataka',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'KER',
                'organization_name' => 'Director Of Ports Govt Kerala',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'MMB',
                'organization_name' => 'Maharashtra Maritime Board',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'OMB',
                'organization_name' => 'Odisha Maritime Board',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'PDY',
                'organization_name' => 'Director of Ports Puducherry',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'TMB',
                'organization_name' => 'Tamil Nadu Maritime Board',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'DAMAN',
                'organization_name' => 'UT AD Of Daman',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'DIU',
                'organization_name' => 'UT ADM Of Diu',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'LD',
                'organization_name' => 'Director Port Lakshadeep',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
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
