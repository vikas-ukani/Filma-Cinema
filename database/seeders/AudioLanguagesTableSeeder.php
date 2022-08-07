<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AudioLanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('audio_languages')->delete();
        
        \DB::table('audio_languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'language' => '{"en":"english"}',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => NULL,
                'status' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'language' => '{"en":"spanish"}',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => NULL,
                'status' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'language' => '{"en":"Bengali"}',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => NULL,
                'status' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'language' => '{"en":"French"}',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => NULL,
                'status' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'language' => '{"en":"Hindi"}',
                'created_at' => now(),
                'updated_at' => now(),
                'image' => NULL,
                'status' => 1,
            ),
        ));
        
        
    }
}