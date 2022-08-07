<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ButtonsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('buttons')->delete();
        
        \DB::table('buttons')->insert(array (
            0 => 
            array (
                'id' => 1,
                'rightclick' => 1,
                'inspect' => 1,
                'goto' => 1,
                'color' => 0,
                'uc_browser' => 1,
                'comming_soon' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'commingsoon_enabled_ip' => NULL,
                'ip_block' => 0,
                'block_ips' => NULL,
                'maintenance' => 1,
                'comming_soon_text' => NULL,
                'remove_subscription' => 0,
                'protip' => 1,
                'multiplescreen' => 0,
                'two_factor' => 0,
                'countviews' => 0,
                'remove_ads' => 0,
                'is_toprated' => 0,
                'toprated_count' => NULL,
                'remove_thumbnail' => 0,
            ),
        ));
        
        
    }
}