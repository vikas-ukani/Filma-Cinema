<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppUiShortingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app_ui_shortings')->delete();
        
        \DB::table('app_ui_shortings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'genre',
                'is_active' => 1,
                'position' => 1,
                'created_at' => NULL,
                'updated_at' => '2022-03-22 12:32:10',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'artist',
                'is_active' => 1,
                'position' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'trending',
                'is_active' => 1,
                'position' => 5,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'movies',
                'is_active' => 1,
                'position' => 3,
                'created_at' => NULL,
                'updated_at' => '2022-03-19 17:14:16',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'tv_series',
                'is_active' => 1,
                'position' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'coming_soon',
                'is_active' => 1,
                'position' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'blog',
                'is_active' => 1,
                'position' => 7,
                'created_at' => NULL,
                'updated_at' => '2022-03-21 11:43:54',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'live',
                'is_active' => 1,
                'position' => 8,
                'created_at' => NULL,
                'updated_at' => '2022-03-21 10:54:40',
            ),
        ));
        
        
    }
}