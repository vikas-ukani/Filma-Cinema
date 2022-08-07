<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HomeBlocksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('home_blocks')->delete();
        
        \DB::table('home_blocks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'movie_id' => 2,
                'tv_series_id' => NULL,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}