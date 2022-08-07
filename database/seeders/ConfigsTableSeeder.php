<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ConfigsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('configs')->delete();
        
        \DB::table('configs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'logo' => 'logo_1550663262logo.png',
                'favicon' => 'favicon.png',
                'livetvicon' => 'livetvicon_1634623645default-tvicon.png',
                'title' => '{"en":"Nexthour","Spanish":"Nexthour","spanish":"Nexthour","FR":"Nexthour","EN":"Nexthour"}',
                'w_email' => 'contact@nexthour.com',
                'verify_email' => 0,
                'download' => 0,
                'free_sub' => 0,
                'free_days' => 40,
                'stripe_pub_key' => '',
                'stripe_secret_key' => '',
                'paypal_mar_email' => '',
                'currency_code' => 'USD',
                'currency_symbol' => 'fa fa-dollar',
                'invoice_add' => '{"en":null}',
                'prime_main_slider' => 0,
                'catlog' => 1,
                'withlogin' => 1,
                'prime_genre_slider' => 1,
                'donation' => 0,
                'donation_link' => NULL,
                'prime_footer' => 1,
                'prime_movie_single' => 1,
                'terms_condition' => '{"en":"<p><strong>Lorem Ipsum<\\/strong>\\u00a0is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<\\/p>","nl":"<p>newvious&nbsp;goodesioanos<\\/p>"}',
                'privacy_pol' => '{"en":"<p><strong>Lorem Ipsum<\\/strong>\\u00a0is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<\\/p>"}',
                'refund_pol' => '{"en":"<p><strong>Lorem Ipsum<\\/strong>\\u00a0is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<\\/p>"}',
                'copyright' => '{"en":"Next Hour | All Rights Reserved."}',
                'stripe_payment' => 0,
                'paypal_payment' => 0,
                'razorpay_payment' => 0,
                'age_restriction' => 1,
                'payu_payment' => 0,
                'bankdetails' => 0,
                'account_no' => NULL,
                'branch' => NULL,
                'account_name' => NULL,
                'ifsc_code' => NULL,
                'bank_name' => NULL,
                'paytm_payment' => 0,
                'paytm_test' => 0,
                'preloader' => 1,
                'fb_login' => 0,
                'gitlab_login' => 0,
                'google_login' => 0,
                'wel_eml' => 0,
                'blog' => 0,
                'is_playstore' => 0,
                'is_appstore' => 0,
                'playstore' => 'https://www.youtube.com/upload',
                'appstore' => 'https://www.youtube.com/upload',
                'user_rating' => 0,
                'comments' => 0,
                'braintree' => 0,
                'paystack' => 0,
                'remove_landing_page' => 0,
                'coinpay' => 0,
                'captcha' => 0,
                'amazon_login' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'mollie_payment' => 0,
                'cashfree_payment' => 0,
                'aws' => 0,
                'omise_payment' => 0,
                'flutterrave_payment' => 0,
                'instamojo_payment' => 0,
                'comments_approval' => 1,
                'payhere_payment' => 0,
                'preloader_img' => 'preloader_1634623645preloader.png',
            ),
        ));
        
        
    }
}