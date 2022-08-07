<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackageFeaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('package_features')->delete();
        
        \DB::table('package_features')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '{"en":"Watch on your laptop, TV, phone and tablet"}',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '{"en":"Full HD and 4K available"}',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '{"en":"Unlimited movies and TV shows"}',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '{"en":"24\\/7 Tech Support"}',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '{"en":"Cancel anytime"}',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}