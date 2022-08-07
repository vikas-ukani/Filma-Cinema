<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LandingPagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('landing_pages')->delete();
        
        \DB::table('landing_pages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'image' => 'landing_page_1604308741home_1.jpg',
                'heading' => '{"en":"Welcome!  Join Next Hour"}',
                'detail' => '{"en":"Join Next Hour to watch the most recent motion pictures, elite TV appears and grant winning Next Hour membership at simply least cost."}',
                'button' => 1,
                'button_text' => '{"en":"Join Next Hour"}',
                'button_link' => 'login',
                'left' => 0,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'image' => 'landing_page_1604308752home_2.jpg',
                'heading' => '{"en":"Don\'t Miss TV Shows"}',
                'detail' => '{"en":"With your Next Hour membership, you approach select US and all TV shows, grant winning Next Hour Original Series and kids and children shows."}',
                'button' => 1,
                'button_text' => '{"en":"Register Now"}',
                'button_link' => 'register',
                'left' => 1,
                'position' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'image' => 'landing_page_1604308763home_3.jpg',
                'heading' => '{"en":"Membership for Movies & TV shows"}',
                'detail' => '{"en":"Notwithstanding boundless gushing, your Next Hour membership incorporates elite Bollywood, Hollywood films, US and all TV shows, grant winning Next Hour Series and kids shows."}',
                'button' => 1,
                'button_text' => '{"en":"Login Now"}',
                'button_link' => 'login',
                'left' => 0,
                'position' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'image' => 'landing_page_1604308776home_4.jpg',
                'heading' => '{"en":"Kids Special"}',
                'detail' => '{"en":"With simple to utilize parental controls and a committed children page, you can appreciate secure, advertisement free children and kids diversion. Children and kids can appreciate famous TV shows."}',
                'button' => 1,
                'button_text' => '{"en":"Get Now"}',
                'button_link' => 'register',
                'left' => 0,
                'position' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}