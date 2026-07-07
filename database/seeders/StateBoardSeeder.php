<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StateBoard;

class StateBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StateBoard::insert([
            [
                'state_id' => 1,
                'board_code' => 'GMB',
                'board_name' => 'Gujarat Maritime Board',
                'status' => 1,
            ],
            [
                'state_id' => 2,
                'board_code' => 'MMB',
                'board_name' => 'Maharashtra Maritime Board',
                'status' => 1,
            ],
            [
                'state_id' => 3,
                'board_code' => 'GSB',
                'board_name' => 'Goa State Board',
                'status' => 1,
            ],
            [
                'state_id' => 4,
                'board_code' => 'TMB',
                'board_name' => 'Tamil Nadu Maritime Board',
                'status' => 1,
            ],
        ]);
    }
}
