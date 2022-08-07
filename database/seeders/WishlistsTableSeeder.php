<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WishlistsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wishlists')->delete();
        
        \DB::table('wishlists')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'movie_id' => 3,
                'season_id' => NULL,
                'added' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'movie_id' => 6,
                'season_id' => NULL,
                'added' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'movie_id' => 7,
                'season_id' => NULL,
                'added' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'movie_id' => NULL,
                'season_id' => 1,
                'added' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}