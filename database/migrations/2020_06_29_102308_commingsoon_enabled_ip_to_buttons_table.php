<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommingsoonEnabledIpToButtonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('buttons') ) {

             Schema::table('buttons', function (Blueprint $table) {
                if (!Schema::hasColumn('buttons','comming_soon')){
                    $table->boolean('comming_soon')->default(1);
                }
               if (!Schema::hasColumn('buttons', 'commingsoon_enabled_ip')){
                    $table->longtext('commingsoon_enabled_ip')->nullable();
                }
                if (!Schema::hasColumn('buttons', 'ip_block')){
                    $table->boolean('ip_block')->default(1);
                }
                if (!Schema::hasColumn('buttons', 'block_ips')){
                    $table->longtext('block_ips')->nullable();
                }
                if (!Schema::hasColumn('buttons','maintenance')){
                  
                    $table->boolean('maintenance')->default(1);
                }
               
                if (!Schema::hasColumn('buttons', 'comming_soon_text')){
                    $table->longtext('comming_soon_text')->nullable();
                }
                if (!Schema::hasColumn('buttons', 'remove_subscription')){
                    $table->boolean('remove_subscription')->default(0);
                }
                 if (!Schema::hasColumn('buttons', 'protip')){
                    $table->boolean('protip')->default(1);
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
        if(Schema::hasTable('buttons')){
            Schema::table('buttons', function (Blueprint $table) {
                $table->dropColumn('commingsoon_enabled_ip');
                $table->dropColumn('ip_block');
                $table->dropColumn('ipblock_ip');
                $table->dropColumn('maintenance');
                $table->dropColumn('comming_soon_text');
                $table->dropColumn('remove_subscription');
                $table->dropColumn('protip');
            });
        }
    }
}
