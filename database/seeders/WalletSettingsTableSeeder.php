<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WalletSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wallet_settings')->delete();
        
        \DB::table('wallet_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'enable_wallet' => 1,
                'paytm_enable' => 1,
                'paypal_enable' => 1,
                'stripe_enable' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}