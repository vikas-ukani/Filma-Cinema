<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnV40 extends Migration
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
                if (Schema::hasColumn('app_configs','amazon_lab_check')){
                    $table->dropColumn('amazon_lab_check');
                
                }
               
            });
        }
        if(Schema::hasTable('configs')){
            Schema::table('configs', function (Blueprint $table) {
                if (Schema::hasColumn('configs','color')){
                    $table->dropColumn('color');
                
                }
                if (Schema::hasColumn('configs','color_dark')){
                    $table->dropColumn('color_dark');
                
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
        //
    }
}
