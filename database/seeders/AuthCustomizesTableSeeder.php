<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AuthCustomizesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('auth_customizes')->delete();
        
        \DB::table('auth_customizes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'image' => '{"en":"auth_page1604309119login.jpg"}',
                'detail' => '{"en":"<h1>Welcome to<br \\/>\\r\\nNext Hour<\\/h1>\\r\\n\\r\\n<h2>Are you ready to join the elite?<\\/h2>"}',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}