<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'image' => NULL,
                'email' => 'admin@mediacity.co.in',
                'verifyToken' => NULL,
                'status' => 1,
                'password' => '$2y$10$NCiYPHZ0wughu1cgWJU98e65QoVjnWz598Uj6T8ez5H3e3Vz6YSDO',
                'google_id' => NULL,
                'facebook_id' => NULL,
                'gitlab_id' => NULL,
                'dob' => NULL,
                'age' => 0,
                'mobile' => NULL,
                'braintree_id' => NULL,
                'code' => NULL,
                'is_admin' => 1,
                'is_assistant' => 0,
                'remember_token' => NULL,
                'is_blocked' => 0,
                'amazon_id' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'stripe_id' => NULL,
                'card_brand' => NULL,
                'card_last_four' => NULL,
                'trial_ends_at' => NULL,
                'google2fa_secret' => NULL,
                'google2fa_enable' => 0,
            ),
        ));
        
        
    }
}