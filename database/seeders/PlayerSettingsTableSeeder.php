<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlayerSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('player_settings')->delete();
        
        \DB::table('player_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'logo' => 'logo.png',
                'logo_enable' => 1,
                'cpy_text' => '2021 Nexthour',
                'share_opt' => 1,
                'auto_play' => NULL,
                'speed' => 1,
                'thumbnail' => NULL,
                'info_window' => 1,
                'skin' => 'minimal_skin_dark',
                'loop_video' => NULL,
                'is_resume' => NULL,
                'player_google_analytics_id' => NULL,
                'subtitle_font_size' => 20,
                'subtitle_color' => '#48a3c6',
                'chromecast' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}