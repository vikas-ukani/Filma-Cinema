<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AffilatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('affilates')->delete();
        
        \DB::table('affilates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code_limit' => 6,
                'about_system' => '<p><strong><span class="marker">How it works ? &nbsp;</span><br />
<br />
Once the system is enabled user will able to put refer code on register screen if refer code is valid then settled amount is given to that referral user&#39;s wallet (IF his wallet is active).</strong><br />
<strong>IF refer code is invalid user will see invalid refer code warning on register screen unless he put correct refer code or remove the refer code.</strong></p>

<p><strong>IF Credit wallet amount on first purchase settings is enabled then after being referred, user need to purchase something. Once their order is delivered successfully then referral code user will get their amount in wallet.&nbsp;</strong></p>

<p><strong>IF Credit wallet amount on first purchase settings is disabled then after being referred, referral code user will get their amount in wallet.&nbsp;</strong><br />
<strong>After Enable the Affiliate system user will have a have a refer screen to share his affiliated link and he will able to trace which user is signup with his refer code on his dashboard under My Account section.</strong></p>

<p><strong>&nbsp;</strong></p>',
                'enable_affilate' => 1,
                'refer_amount' => 0.01,
                'enable_purchase' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}