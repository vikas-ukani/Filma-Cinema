<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('oauth_clients')->delete();
        
        \DB::table('oauth_clients')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => NULL,
                'name' => 'Nexthour Personal Access Client',
                'secret' => 'Z9kvpHyT5nzRQrGKvLoKxXces8bxDzexeqZVP5sA',
                'provider' => NULL,
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2019-12-09 09:59:26',
                'updated_at' => '2019-12-09 09:59:26',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => NULL,
                'name' => 'Nexthour Password Grant Client',
                'secret' => 'C2VrZuB5yr78fKGJcbPMtS4k6U1DAWePGhNu4Uq8',
                'provider' => NULL,
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2019-12-09 09:59:27',
                'updated_at' => '2019-12-09 09:59:27',
            ),
        ));
        
        
    }
}