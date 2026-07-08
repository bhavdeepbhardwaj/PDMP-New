<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StateBoard;

class StateBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stateBoards = [

            [
                'state_id'    => 1,
                'board_code'  => 'AP',
                'board_name'  => 'Directorate Of Port Govt Of AP',
                'description' => 'Directorate Of Port Govt Of AP',
                'status'      => 1,
            ],

            [
                'state_id'    => 3,
                'board_code'  => 'AN',
                'board_name'  => 'Port Management Board A N',
                'description' => 'Port Management Board A N',
                'status'      => 1,
            ],

            [
                'state_id'    => 11,
                'board_code'  => 'GOA',
                'board_name'  => 'Capt of Ports GOVT OF GOA',
                'description' => 'Capt of Ports GOVT OF GOA',
                'status'      => 1,
            ],

            [
                'state_id'    => 12,
                'board_code'  => 'GMB',
                'board_name'  => 'Gujarat Maritime Board',
                'description' => 'Gujarat Maritime Board',
                'status'      => 1,
            ],

            [
                'state_id'    => 17,
                'board_code'  => 'KAR',
                'board_name'  => 'Directorate Of Ports Karnataka',
                'description' => 'Directorate Of Ports Karnataka',
                'status'      => 1,
            ],

            [
                'state_id'    => 18,
                'board_code'  => 'KER',
                'board_name'  => 'Director Of Ports Govt Kerala',
                'description' => 'Director Of Ports Govt Kerala',
                'status'      => 1,
            ],

            [
                'state_id'    => 21,
                'board_code'  => 'MMB',
                'board_name'  => 'Maharashtra Maritime Board',
                'description' => 'Maharashtra Maritime Board',
                'status'      => 1,
            ],

            [
                'state_id'    => 26,
                'board_code'  => 'ODI',
                'board_name'  => 'Directorate Of Ports Odisha',
                'description' => 'Directorate Of Ports Odisha',
                'status'      => 1,
            ],

            [
                'state_id'    => 27,
                'board_code'  => 'PDY',
                'board_name'  => 'Director of Ports Puducherry',
                'description' => 'Director of Ports Puducherry',
                'status'      => 1,
            ],

            [
                'state_id'    => 31,
                'board_code'  => 'TMB',
                'board_name'  => 'Tamil Nadu Maritime Board',
                'description' => 'Tamil Nadu Maritime Board',
                'status'      => 1,
            ],

            [
                'state_id'    => 9,
                'board_code'  => 'DAMAN',
                'board_name'  => 'UT AD Of Daman',
                'description' => 'UT AD Of Daman',
                'status'      => 1,
            ],

            [
                'state_id'    => 9,
                'board_code'  => 'DIU',
                'board_name'  => 'UT ADM Of Diu',
                'description' => 'UT ADM Of Diu',
                'status'      => 1,
            ],

            [
                'state_id'    => 19,
                'board_code'  => 'LD',
                'board_name'  => 'Director Port Lakshadeep',
                'description' => 'Director Port Lakshadeep',
                'status'      => 1,
            ],

            [
                'state_id'    => 10,
                'board_code'  => 'TEST',
                'board_name'  => 'Test State Board',
                'description' => 'Test State Board',
                'status'      => 0,
            ],

        ];

        foreach ($stateBoards as $stateBoard) {
            StateBoard::updateOrCreate(
                [
                    'board_code' => $stateBoard['board_code'],
                ],
                $stateBoard
            );
        }
    }
}
