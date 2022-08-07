<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TvSeriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tv_series')->delete();
        
        \DB::table('tv_series')->insert(array (
            0 => 
            array (
                'id' => 1,
                'keyword' => '{"en":null}',
                'description' => '{"en":null}',
                'title' => 'Sex Education',
                'tmdb_id' => '81356',
                'tmdb' => 'Y',
                'fetch_by' => 'title',
                'thumbnail' => 'tmdb_8j12tohv1NBZNmpU93f47sAKBbw.jpg',
                'poster' => 'poster_bxU79lpl8ZQAVJ72155kqWkuqMu.jpg',
                'genre_id' => '8,9',
                'detail' => '{"en":"Inexperienced Otis channels his sex therapist mom when he teams up with rebellious Maeve to set up an underground sex therapy clinic at school."}',
                'rating' => 8.4,
                'episode_runtime' => NULL,
                'maturity_rating' => 'all age',
                'featured' => 0,
                'type' => 'T',
                'status' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_custom_label' => 0,
                'label_id' => NULL,
                'is_upcoming' => 0,
                'upcoming_date' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'keyword' => '{"en":null}',
                'description' => '{"en":null}',
                'title' => 'Squid Game',
                'tmdb_id' => '93405',
                'tmdb' => 'Y',
                'fetch_by' => 'title',
                'thumbnail' => 'tmdb_dDlEmu3EZ0Pgg93K2SVNLCjCSvE.jpg',
                'poster' => 'poster_oaGvjB0DvdhXhOAuADfHb261ZHa.jpg',
                'genre_id' => '10,11,9',
                'detail' => '{"en":"Hundreds of cash-strapped players accept a strange invitation to compete in children\'s games\\u2014with high stakes. But, a tempting prize awaits the victor."}',
                'rating' => 7.8,
                'episode_runtime' => NULL,
                'maturity_rating' => 'all age',
                'featured' => 1,
                'type' => 'T',
                'status' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_custom_label' => 0,
                'label_id' => NULL,
                'is_upcoming' => 0,
                'upcoming_date' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'keyword' => '{"en":null}',
                'description' => '{"en":null}',
                'title' => 'La Brea',
                'tmdb_id' => '128839',
                'tmdb' => 'Y',
                'fetch_by' => 'title',
                'thumbnail' => 'tmdb_nsoRsiUEakEO7VMIoa6Jkw4cPHs.jpg',
                'poster' => 'poster_1HiLMi66dO8Ia7EsPT2D9FwK5tL.jpg',
                'genre_id' => '9,13,10',
                'detail' => '{"en":"When a massive sinkhole mysteriously opens in Los Angeles, it tears a family in half, separating mother and son from father and daughter. When part of the family finds themselves in an unexplainable primeval world, alongside a disparate group of strangers, they must work to survive and uncover the mystery of where they are and if there is a way back home."}',
                'rating' => 7.8,
                'episode_runtime' => NULL,
                'maturity_rating' => 'all age',
                'featured' => 0,
                'type' => 'T',
                'status' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'is_custom_label' => 0,
                'label_id' => NULL,
                'is_upcoming' => 0,
                'upcoming_date' => NULL,
            ),
        ));
        
        
    }
}