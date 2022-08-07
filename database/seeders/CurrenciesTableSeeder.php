<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('currencies')->delete();
        
        \DB::table('currencies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Indian Rupee',
                'code' => 'INR',
                'symbol' => '₹',
                'format' => '1,0.00₹',
                'exchange_rate' => '75.298292',
                'payment_method' => '["flutterrave","instamojo","paytm","mollie","cashfree","razorpay"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'format' => '$1,0.00',
                'exchange_rate' => '1',
                'payment_method' => '["stripe","paypal","braintree","omise","payhere","flutterrave","mollie"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'format' => '1.0,00 €',
                'exchange_rate' => '0.863677',
                'payment_method' => '["stripe","paypal","braintree","omise","payhere","flutterrave"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Sri Lanka Rupee',
                'code' => 'LKR',
                'symbol' => '₨',
                'format' => '₨ 1,0.',
                'exchange_rate' => '202.450844',
                'payment_method' => '["payhere"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'UAE Dirham',
                'code' => 'AED',
                'symbol' => 'دإ‏',
                'format' => 'دإ‏ 1,0.00',
                'exchange_rate' => '3.6731',
                'payment_method' => '["mollie","cashfree"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Nigeria, Naira',
                'code' => 'NGN',
                'symbol' => '₦',
                'format' => '₦1,0.00',
                'exchange_rate' => '411.92',
                'payment_method' => '["stripe","flutterrave","paystack"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Angola, Kwanza',
                'code' => 'AOA',
                'symbol' => 'Kz',
                'format' => 'Kz1,0.00',
                'exchange_rate' => '597.698',
                'payment_method' => '["stripe"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Tunisian Dinar',
                'code' => 'TND',
                'symbol' => 'د.ت.‏',
                'format' => 'د.ت.‏ 1,0.000',
                'exchange_rate' => '2.8235',
                'payment_method' => '["stripe"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Kenyan Shilling',
                'code' => 'KES',
                'symbol' => 'S',
                'format' => 'S1,0.00',
                'exchange_rate' => '110.9',
                'payment_method' => '["stripe"]',
                'active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}