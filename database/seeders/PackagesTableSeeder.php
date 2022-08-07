<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('packages')->delete();
        
        \DB::table('packages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'plan_id' => 'basic12',
                'name' => '{"en":"Basic"}',
                'currency' => 'USD',
                'currency_symbol' => 'fa fa-dollar',
                'amount' => '0.00',
                'interval' => '{"en":"week"}',
                'interval_count' => 1,
                'trial_period_days' => NULL,
                'status' => 'active',
                'screens' => 1,
                'download' => 0,
                'downloadlimit' => NULL,
                'delete_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'free' => 1,
                'feature' => '["1","3","5"]',
                'ads_in_web' => 0,
                'ads_in_app' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'plan_id' => 'standard12',
                'name' => '{"en":"Standard"}',
                'currency' => 'USD',
                'currency_symbol' => 'fa fa-dollar',
                'amount' => '199.00',
                'interval' => '{"en":"month"}',
                'interval_count' => 1,
                'trial_period_days' => NULL,
                'status' => 'active',
                'screens' => 2,
                'download' => 0,
                'downloadlimit' => NULL,
                'delete_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'free' => 0,
                'feature' => '["1","2","4","5"]',
                'ads_in_web' => 0,
                'ads_in_app' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'plan_id' => 'permium12',
                'name' => '{"en":"Permium"}',
                'currency' => 'USD',
                'currency_symbol' => 'fa fa-dollar',
                'amount' => '999.00',
                'interval' => '{"en":"year"}',
                'interval_count' => 1,
                'trial_period_days' => NULL,
                'status' => 'upcoming',
                'screens' => 4,
                'download' => 0,
                'downloadlimit' => NULL,
                'delete_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'free' => 0,
                'feature' => '["1","2","3","4","5"]',
                'ads_in_web' => 0,
                'ads_in_app' => 0,
            ),
        ));
        
        
    }
}