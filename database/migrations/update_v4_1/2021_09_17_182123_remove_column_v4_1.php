<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnV41 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('app_configs')){
            Schema::table('app_configs', function (Blueprint $table) {
                if (Schema::hasColumn('app_configs','ADMOB_APP_KEY')){
                    $table->dropColumn('ADMOB_APP_KEY');
                
                }
                if (Schema::hasColumn('app_configs','banner_admob')){
                    $table->dropColumn('banner_admob');
                
                }
                if (Schema::hasColumn('app_configs','banner_id')){
                    $table->dropColumn('banner_id');
                
                }
                if (Schema::hasColumn('app_configs','interstitial_admob')){
                    $table->dropColumn('interstitial_admob');
                
                }
                if (Schema::hasColumn('app_configs','interstitial_id')){
                    $table->dropColumn('interstitial_id');
                
                }
                if (Schema::hasColumn('app_configs','rewarded_admob')){
                    $table->dropColumn('rewarded_admob');
                
                }
                if (Schema::hasColumn('app_configs','rewarded_id')){
                    $table->dropColumn('rewarded_id');
                
                }
                if (Schema::hasColumn('app_configs','native_admob')){
                    $table->dropColumn('native_admob');
                
                }
                if (Schema::hasColumn('app_configs','native_id')){
                    $table->dropColumn('native_id');
                
                }
               
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
