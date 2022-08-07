<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPlayerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('player_settings') ) {
            Schema::table('player_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('player_settings', 'chromecast')){
                    $table->boolean('chromecast')->nullable()->after('subtitle_color');
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
        if(Schema::hasTable('player_settings')){
            Schema::table('player_settings', function (Blueprint $table) {
                $table->dropColumn('chromecast');
            });
        }
    }
}
