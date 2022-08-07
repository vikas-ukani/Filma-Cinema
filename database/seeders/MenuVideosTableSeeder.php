<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuVideosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_videos')->delete();
        
        \DB::table('menu_videos')->insert(array (
            0 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'movie_id' => 2,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'movie_id' => 4,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'movie_id' => 5,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'movie_id' => 6,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'movie_id' => NULL,
                'tv_series_id' => 1,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            5 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'movie_id' => NULL,
                'tv_series_id' => 2,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            6 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'movie_id' => NULL,
                'tv_series_id' => 3,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            7 => 
            array (
                'id' => 12,
                'menu_id' => 1,
                'movie_id' => 1,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            8 => 
            array (
                'id' => 13,
                'menu_id' => 1,
                'movie_id' => 3,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            9 => 
            array (
                'id' => 14,
                'menu_id' => 1,
                'movie_id' => 7,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            10 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'movie_id' => 8,
                'tv_series_id' => NULL,
                'live_event_id' => NULL,
                'audio_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}