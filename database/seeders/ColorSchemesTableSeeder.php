<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ColorSchemesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('color_schemes')->delete();
        
        \DB::table('color_schemes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'color_scheme' => 'dark',
                'default_navigation_color' => '#111111FA',
                'custom_navigation_color' => NULL,
                'default_text_color' => '#48A3C6',
                'custom_text_color' => NULL,
                'default_text_on_color' => '#90DFFE',
                'custom_text_on_color' => NULL,
                'default_back_to_top_color' => '#111',
                'custom_back_to_top_color' => NULL,
                'default_back_to_top_bgcolor' => '#FFF',
                'custom_back_to_top_bgcolor' => NULL,
                'default_back_to_top_bgcolor_on_hover' => '#48A3C6',
                'custom_back_to_top_bgcolor_on_hover' => NULL,
                'default_back_to_top_color_on_hover' => '#FFF',
                'custom_back_to_top_color_on_hover' => NULL,
                'default_footer_background_color' => '#111',
                'custom_footer_background_color' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}