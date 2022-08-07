<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WatchHistoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('watch_histories')->delete();
        
        \DB::table('watch_histories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'movie_id' => 1,
                'tv_id' => NULL,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'movie_id' => 3,
                'tv_id' => NULL,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'movie_id' => NULL,
                'tv_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'movie_id' => NULL,
                'tv_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => 
            array (
                'id' => 5,
                'movie_id' => NULL,
                'tv_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            5 => 
            array (
                'id' => 6,
                'movie_id' => 2,
                'tv_id' => NULL,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}