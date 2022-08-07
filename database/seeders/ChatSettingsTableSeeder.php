<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChatSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('chat_settings')->delete();
        
        \DB::table('chat_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'messanger',
                'enable_messanger' => 0,
                'script' => NULL,
                'mobile' => NULL,
                'text' => NULL,
                'header' => NULL,
                'color' => '#52D668',
                'size' => 30,
                'enable_whatsapp' => 0,
                'position' => 'right',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'whatsapp',
                'enable_messanger' => 0,
                'script' => NULL,
                'mobile' => '9999999999',
                'text' => 'Hey! We can help you?',
                'header' => 'Chat with us',
                'color' => '#4fd896',
                'size' => 30,
                'enable_whatsapp' => 0,
                'position' => 'right',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}