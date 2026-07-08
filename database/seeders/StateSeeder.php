<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['state_name' => 'Andhra Pradesh', 'state_code' => 'AP', 'description' => 'State of Andhra Pradesh'],
            ['state_name' => 'Arunachal Pradesh', 'state_code' => 'AR', 'description' => 'State of Arunachal Pradesh'],
            ['state_name' => 'Andaman and Nicobar Islands', 'state_code' => 'AN', 'description' => 'Union Territory of Andaman and Nicobar Islands'],
            ['state_name' => 'Assam', 'state_code' => 'AS', 'description' => 'State of Assam'],
            ['state_name' => 'Bihar', 'state_code' => 'BR', 'description' => 'State of Bihar'],
            ['state_name' => 'Chandigarh', 'state_code' => 'CH', 'description' => 'Union Territory of Chandigarh'],
            ['state_name' => 'Chhattisgarh', 'state_code' => 'CG', 'description' => 'State of Chhattisgarh'],
            ['state_name' => 'Dadar and Nagar Haveli', 'state_code' => 'DH', 'description' => 'Union Territory of Dadar and Nagar Haveli'],
            ['state_name' => 'Daman and Diu', 'state_code' => 'DD', 'description' => 'Union Territory of Daman and Diu'],
            ['state_name' => 'Delhi', 'state_code' => 'DL', 'description' => 'National Capital Territory of Delhi'],
            ['state_name' => 'Goa', 'state_code' => 'GA', 'description' => 'State of Goa'],
            ['state_name' => 'Gujarat', 'state_code' => 'GJ', 'description' => 'State of Gujarat'],
            ['state_name' => 'Haryana', 'state_code' => 'HR', 'description' => 'State of Haryana'],
            ['state_name' => 'Himachal Pradesh', 'state_code' => 'HP', 'description' => 'State of Himachal Pradesh'],
            ['state_name' => 'Jammu and Kashmir', 'state_code' => 'JK', 'description' => 'Union Territory of Jammu and Kashmir'],
            ['state_name' => 'Jharkhand', 'state_code' => 'JH', 'description' => 'State of Jharkhand'],
            ['state_name' => 'Karnataka', 'state_code' => 'KA', 'description' => 'State of Karnataka'],
            ['state_name' => 'Kerala', 'state_code' => 'KL', 'description' => 'State of Kerala'],
            // ['state_name' => 'Ladakh', 'state_code' => 'LA', 'description' => 'Union Territory of Ladakh'],
            ['state_name' => 'Lakshadeep', 'state_code' => 'LD', 'description' => 'Union Territory of Lakshadweep'],
            ['state_name' => 'Madhya Pradesh', 'state_code' => 'MP', 'description' => 'State of Madhya Pradesh'],
            ['state_name' => 'Maharashtra', 'state_code' => 'MH', 'description' => 'State of Maharashtra'],
            ['state_name' => 'Manipur', 'state_code' => 'MN', 'description' => 'State of Manipur'],
            ['state_name' => 'Meghalaya', 'state_code' => 'ML', 'description' => 'State of Meghalaya'],
            ['state_name' => 'Mizoram', 'state_code' => 'MZ', 'description' => 'State of Mizoram'],
            ['state_name' => 'Nagaland', 'state_code' => 'NL', 'description' => 'State of Nagaland'],
            ['state_name' => 'Odisha', 'state_code' => 'OD', 'description' => 'State of Odisha'],
            ['state_name' => 'Puducherry', 'state_code' => 'PY', 'description' => 'Union Territory of Puducherry'],
            ['state_name' => 'Punjab', 'state_code' => 'PB', 'description' => 'State of Punjab'],
            ['state_name' => 'Rajasthan', 'state_code' => 'RJ', 'description' => 'State of Rajasthan'],
            ['state_name' => 'Sikkim', 'state_code' => 'SK', 'description' => 'State of Sikkim'],
            ['state_name' => 'Tamil Nadu', 'state_code' => 'TN', 'description' => 'State of Tamil Nadu'],
            ['state_name' => 'Telangana', 'state_code' => 'TS', 'description' => 'State of Telangana'],
            ['state_name' => 'Tripura', 'state_code' => 'TR', 'description' => 'State of Tripura'],
            ['state_name' => 'Uttaranchal', 'state_code' => 'AU', 'description' => 'State of Uttaranchal'],
            ['state_name' => 'Uttar Pradesh', 'state_code' => 'UP', 'description' => 'State of Uttar Pradesh'],
            ['state_name' => 'Uttarakhand', 'state_code' => 'UK', 'description' => 'State of Uttarakhand'],
            ['state_name' => 'West Bengal', 'state_code' => 'WB', 'description' => 'State of West Bengal'],
        ];

        foreach ($states as $state) {

            State::updateOrCreate(
                [
                    'state_name' => $state['state_name'],
                    'state_code' => $state['state_code'],
                ],
                $state
            );
        }
    }
}
