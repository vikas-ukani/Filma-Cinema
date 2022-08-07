<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomPagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('custom_pages')->delete();
        
        \DB::table('custom_pages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '{"en":"About"}',
                'slug' => 'about',
                'in_show_menu' => 1,
                'detail' => '{"en":"<p><strong>Lorem Ipsum<\\/strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, w',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}