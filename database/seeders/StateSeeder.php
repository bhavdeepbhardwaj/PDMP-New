<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::insert([
            [
                'state_code' => 'GJ',
                'state_name' => 'Gujarat',
                'status' => 1,
            ],
            [
                'state_code' => 'MH',
                'state_name' => 'Maharashtra',
                'status' => 1,
            ],
            [
                'state_code' => 'GA',
                'state_name' => 'Goa',
                'status' => 1,
            ],
            [
                'state_code' => 'TN',
                'state_name' => 'Tamil Nadu',
                'status' => 1,
            ],
        ]);
    }
}
