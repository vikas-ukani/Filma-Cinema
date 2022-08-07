<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('genres')->delete();
        
        \DB::table('genres')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '{"en":"Science Fiction"}',
                'image' => NULL,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '{"en":"Action"}',
                'image' => NULL,
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '{"en":"Crime"}',
                'image' => NULL,
                'position' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '{"en":"Thriller"}',
                'image' => NULL,
                'position' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '{"en":"Animation"}',
                'image' => NULL,
                'position' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '{"en":"Adventure"}',
                'image' => NULL,
                'position' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '{"en":"Fantasy"}',
                'image' => NULL,
                'position' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            7 => 
            array (
                'id' => 8,
                'name' => '{"en":"Comedy"}',
                'image' => NULL,
                'position' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            8 => 
            array (
                'id' => 9,
                'name' => '{"en":"Drama"}',
                'image' => NULL,
                'position' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '{"en":"Action & Adventure"}',
                'image' => NULL,
                'position' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            10 => 
            array (
                'id' => 11,
                'name' => '{"en":"Mystery"}',
                'image' => NULL,
                'position' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            11 => 
            array (
                'id' => 12,
                'name' => '{"en":"Horror"}',
                'image' => NULL,
                'position' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            12 => 
            array (
                'id' => 13,
                'name' => '{"en":"Sci-Fi & Fantasy"}',
                'image' => NULL,
                'position' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            13 => 
            array (
                'id' => 14,
                'name' => '{"en":"History"}',
                'image' => NULL,
                'position' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            14 => 
            array (
                'id' => 15,
                'name' => '{"en":"Romance"}',
                'image' => NULL,
                'position' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}