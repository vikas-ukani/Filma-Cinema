<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuSectionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_sections')->delete();
        
        \DB::table('menu_sections')->insert(array (
            0 => 
            array (
                'id' => 50,
                'menu_id' => 1,
                'section_id' => 1,
                'item_limit' => 20,
                'view' => 1,
                'order' => 1,
            ),
            1 => 
            array (
                'id' => 51,
                'menu_id' => 1,
                'section_id' => 2,
                'item_limit' => 10,
                'view' => 1,
                'order' => 1,
            ),
            2 => 
            array (
                'id' => 52,
                'menu_id' => 1,
                'section_id' => 3,
                'item_limit' => 20,
                'view' => 1,
                'order' => 1,
            ),
            3 => 
            array (
                'id' => 53,
                'menu_id' => 1,
                'section_id' => 4,
                'item_limit' => 20,
                'view' => 1,
                'order' => 1,
            ),
            4 => 
            array (
                'id' => 54,
                'menu_id' => 1,
                'section_id' => 6,
                'item_limit' => 10,
                'view' => 1,
                'order' => 1,
            ),
            5 => 
            array (
                'id' => 55,
                'menu_id' => 1,
                'section_id' => 7,
                'item_limit' => NULL,
                'view' => 1,
                'order' => 1,
            ),
            6 => 
            array (
                'id' => 56,
                'menu_id' => 1,
                'section_id' => 9,
                'item_limit' => NULL,
                'view' => 1,
                'order' => 1,
            ),
        ));
        
        
    }
}