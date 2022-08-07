<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EpisodesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('episodes')->delete();
        
        \DB::table('episodes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'seasons_id' => 1,
                'tmdb_id' => '1639849',
                'thumbnail' => 'tmdb_4m4Dt8sZJpoZ0hIfTxiNefq0rhT.jpg',
                'episode_no' => 1,
                'title' => 'Episode 1',
                'tmdb' => 'Y',
                'duration' => '120',
                'detail' => '{"en":"Despite the ministrations of sex therapist mom Jean and encouragement from pal Eric, Otis worries that he can\'t get it on. He\'s not the only one."}',
                'a_language' => NULL,
                'subtitle' => 0,
                'released' => '2019-01-11',
                'type' => 'E',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'seasons_id' => 1,
                'tmdb_id' => '1666360',
                'thumbnail' => 'tmdb_uCFl2rMgA0h7zkqMuSRdJKzhxOm.jpg',
                'episode_no' => 2,
                'title' => 'Episode 2',
                'tmdb' => 'Y',
                'duration' => '65',
                'detail' => '{"en":"Egged on by Maeve\\u2014and finding that dispensing sex tips is tougher than he thought\\u2014Otis tries offering free advice at a classmate\'s house party."}',
                'a_language' => NULL,
                'subtitle' => 0,
                'released' => '2019-01-11',
                'type' => 'E',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'seasons_id' => 3,
                'tmdb_id' => '1922715',
                'thumbnail' => 'tmdb_vMFJS9LIUUAmQ1thq4vJ7iHKwRz.jpg',
                'episode_no' => 1,
                'title' => 'Red Light, Green Light',
                'tmdb' => 'Y',
                'duration' => '110',
                'detail' => '{"en":"Hoping to win easy money, a broke and desperate Gi-hun agrees to take part in an enigmatic game. Not long into the first round, unforeseen horrors unfold."}',
                'a_language' => NULL,
                'subtitle' => 0,
                'released' => '2021-09-17',
                'type' => 'E',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'seasons_id' => 4,
                'tmdb_id' => '3062539',
                'thumbnail' => 'tmdb_eKkniWvoxXhd0AqCcL6zsIZ1cER.jpg',
                'episode_no' => 1,
                'title' => 'Pilot',
                'tmdb' => 'Y',
                'duration' => '105',
                'detail' => '{"en":"When a massive sinkhole opens in Los Angeles, the Harris family is split in two. Eve and her son are sent to a mysterious primeval world. Gavin discovers that the visions that have plagued him for years might hold the key to bringing them home."}',
                'a_language' => NULL,
                'subtitle' => 0,
                'released' => '2021-09-28',
                'type' => 'E',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}