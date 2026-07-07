<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Port::insert([
            [
                'state_board_id' => 1,
                'port_code' => 'MUNDRA',
                'port_name' => 'Mundra Port',
                'port_type_id' => 2,
                'status' => 1,
            ],
            [
                'state_board_id' => 1,
                'port_code' => 'PIPAVAV',
                'port_name' => 'Pipavav Port',
                'port_type_id' => 2,
                'status' => 1,
            ],
            [
                'state_board_id' => 2,
                'port_code' => 'JNPT',
                'port_name' => 'Jawaharlal Nehru Port',
                'port_type_id' => 1,
                'status' => 1,
            ],
            [
                'state_board_id' => 3,
                'port_code' => 'MORMUGAO',
                'port_name' => 'Mormugao Port',
                'port_type_id' => 1,
                'status' => 1,
            ],
        ]);
    }
}
