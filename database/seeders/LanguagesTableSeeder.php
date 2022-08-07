<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('languages')->delete();
        
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'local' => 'en',
                'name' => 'English',
                'def' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'rtl' => 0,
            ),
        ));
        
        
    }
}