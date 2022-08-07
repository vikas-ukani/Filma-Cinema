<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuGenreShowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_genre_shows')->delete();
        
        \DB::table('menu_genre_shows')->insert(array (
            0 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'menu_section_id' => 2,
                'genre_id' => 2,
            ),
        ));
        
        
    }
}