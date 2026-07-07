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
        Organization::insert([
            [
                'organization_code' => 'MOPSW',
                'organization_name' => 'Ministry of Ports, Shipping and Waterways',
                'description' => 'Government of India',
                'status' => 1,
            ],
            [
                'organization_code' => 'GMB',
                'organization_name' => 'Gujarat Maritime Board',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
            [
                'organization_code' => 'MMB',
                'organization_name' => 'Maharashtra Maritime Board',
                'description' => 'State Maritime Board',
                'status' => 1,
            ],
        ]);
    }
}
