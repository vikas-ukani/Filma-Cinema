<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('seos')->delete();
        
        \DB::table('seos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'author' => '{"en":"Next Hour - Movie Tv Show & Video Subscription Portal Cms"}',
                'fb' => NULL,
                'google' => NULL,
                'metadata' => '{"en":"this ts a next hour"}',
                'description' => '{"en":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."}',
                'keyword' => '{"en":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."}',
                'created_at' => NULL,
                'updated_at' => now(),
            ),
        ));
        
        
    }
}