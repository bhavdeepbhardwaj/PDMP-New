<?php

namespace Database\Seeders;

use App\Models\PortCategory;
use Illuminate\Database\Seeder;

class PortCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            [
                'category_code' => 'MAJOR',
                'name' => 'Major Port',
                'description' => 'Major Ports under Central Government.',
                'status' => true,
                'is_deleted' => false,
            ],

            [
                'category_code' => 'NON_MAJOR',
                'name' => 'Non Major Port',
                'description' => 'Non Major Ports under State Maritime Boards.',
                'status' => true,
                'is_deleted' => false,
            ],

        ];

        foreach ($categories as $category) {

            PortCategory::updateOrCreate(

                [
                    'category_code' => $category['category_code'],
                ],

                $category

            );
        }
    }
}
