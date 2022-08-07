<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackageMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('package_menu')->delete();
        
        \DB::table('package_menu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'pkg_id' => 1,
                'package_id' => 'basic12',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'pkg_id' => 2,
                'package_id' => 'standard12',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'pkg_id' => 3,
                'package_id' => 'permium12',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
        
        
    }
}