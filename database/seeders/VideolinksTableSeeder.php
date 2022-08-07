<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VideolinksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('videolinks')->delete();
        
        \DB::table('videolinks')->insert(array (
            0 => 
            array (
                'id' => 1,
                'movie_id' => 1,
                'episode_id' => NULL,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'movie_id' => 2,
                'episode_id' => NULL,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'https://www.youtube.com/watch?v=DLksA4HgkXs',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'movie_id' => 3,
                'episode_id' => NULL,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'https://vimeo.com/8341236',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'movie_id' => 4,
                'episode_id' => NULL,
                'type' => 'iframeurl',
                'iframeurl' => 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                'ready_url' => NULL,
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'movie_id' => NULL,
                'episode_id' => 1,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'movie_id' => NULL,
                'episode_id' => 2,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'https://www.youtube.com/watch?v=eUIDMSdPrh8',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'movie_id' => NULL,
                'episode_id' => 3,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'movie_id' => 5,
                'episode_id' => NULL,
                'type' => 'iframeurl',
                'iframeurl' => NULL,
                'ready_url' => NULL,
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'movie_id' => 6,
                'episode_id' => NULL,
                'type' => 'iframeurl',
                'iframeurl' => NULL,
                'ready_url' => NULL,
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'movie_id' => NULL,
                'episode_id' => 4,
                'type' => 'readyurl',
                'iframeurl' => NULL,
                'ready_url' => 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'movie_id' => 7,
                'episode_id' => NULL,
                'type' => 'iframeurl',
                'iframeurl' => NULL,
                'ready_url' => NULL,
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'movie_id' => 8,
                'episode_id' => NULL,
                'type' => 'iframeurl',
                'iframeurl' => NULL,
                'ready_url' => NULL,
                'url_360' => NULL,
                'url_480' => NULL,
                'url_720' => NULL,
                'url_1080' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'upload_video' => NULL,
            ),
        ));
        
        
    }
}