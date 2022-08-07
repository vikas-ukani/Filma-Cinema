<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeasonsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('seasons')->delete();
        
        \DB::table('seasons')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tv_series_id' => 1,
                'tmdb_id' => '107288',
                'season_no' => 1,
                'season_slug' => 'sex-education-season-1',
                'tmdb' => 'Y',
                'publish_year' => '2019',
                'thumbnail' => 'tmdb_u3eoZguH2tqLLKqRqWjvisF0d2U.jpg',
                'poster' => NULL,
                'actor_id' => '21,22,23,24,25',
                'a_language' => '1',
                'detail' => '{"en":"Insecure Otis has all the answers when it comes to sex advice, thanks to his therapist mom. So rebel Maeve proposes a school sex-therapy clinic."}',
                'featured' => 1,
                'type' => 'S',
                'is_protect' => 0,
                'password' => NULL,
                'trailer_url' => 'https://youtu.be/v5NE--A0WFk',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'tv_series_id' => 1,
                'tmdb_id' => '136055',
                'season_no' => 2,
                'season_slug' => 'sex-education-season-2',
                'tmdb' => 'Y',
                'publish_year' => '2020',
                'thumbnail' => 'tmdb_rdcY9VJ5uVsCvCxdHoyZzXtKp2E.jpg',
                'poster' => NULL,
                'actor_id' => '26,27,28,29,30',
                'a_language' => NULL,
                'detail' => '{"en":"Otis finally loosens up\\u2014often and epically\\u2014but the pressure\\u2019s on to perform as chlamydia hits the school and mates struggle with new issues."}',
                'featured' => 0,
                'type' => 'S',
                'is_protect' => 0,
                'password' => NULL,
                'trailer_url' => 'https://youtu.be/qZhb0Vl_BaM',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'tv_series_id' => 2,
                'tmdb_id' => '131977',
                'season_no' => 1,
                'season_slug' => 'squid-game-season-1',
                'tmdb' => 'Y',
                'publish_year' => '2021',
                'thumbnail' => 'tmdb_lDm3AjG0ZsPRAz1qHD0em8pzTTp.jpg',
                'poster' => NULL,
                'actor_id' => '31,32,33,34,35',
                'a_language' => '1',
                'detail' => '{"en":"Hundreds of cash-strapped players accept a strange invitation to compete in children\'s games. Inside, a tempting prize awaits \\u2014 with deadly high stakes."}',
                'featured' => 1,
                'type' => 'S',
                'is_protect' => 0,
                'password' => NULL,
                'trailer_url' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'tv_series_id' => 3,
                'tmdb_id' => '201640',
                'season_no' => 1,
                'season_slug' => 'la-brea-season-1',
                'tmdb' => 'Y',
                'publish_year' => '2021',
                'thumbnail' => 'tmdb_nsoRsiUEakEO7VMIoa6Jkw4cPHs.jpg',
                'poster' => NULL,
                'actor_id' => '46,47,48,49,50',
                'a_language' => NULL,
                'detail' => '{"en":""}',
                'featured' => 0,
                'type' => 'S',
                'is_protect' => 0,
                'password' => NULL,
                'trailer_url' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}