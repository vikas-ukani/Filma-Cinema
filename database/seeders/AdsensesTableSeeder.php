<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdsensesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('adsenses')->delete();
        
        \DB::table('adsenses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => '<script type="text/javascript">
google_ad_client = "";  
google_ad_slot = "99*****99"; 
google_ad_width = 728;
google_ad_height =  90; 

</script>',
                'status' => 0,
                'ishome' => 0,
                'isviewall' => 0,
                'issearch' => 0,
                'iswishlist' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}