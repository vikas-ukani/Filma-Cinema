<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SplashScreensTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('splash_screens')->delete();
        
        \DB::table('splash_screens')->insert(array (
            0 => 
            array (
                'id' => 1,
                'image' => 'splashscreen_1606805126image.jpg',
                'logo_enable' => 1,
                'logo' => 'splashscreen_1606805126logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}