<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppConfigsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app_configs')->delete();
        
        \DB::table('app_configs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'logo' => 'applogo_1606642921logo.png',
                'title' => 'Nexthour',
                'stripe_payment' => 0,
                'paypal_payment' => 0,
                'razorpay_payment' => 0,
                'brainetree_payment' => 0,
                'paystack_payment' => 0,
                'bankdetails' => 0,
                'fb_check' => 0,
                'google_login' => 0,
                'git_lab_check' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'inapp_payment' => 0,
                'push_key' => 0,
                'remove_ads' => 0,
                'paytm_payment' => 0,
                'amazon_login' => 0,
                'is_admob' => 0,
                'instamojo_payment' => 0,
            ),
        ));
        
        
    }
}