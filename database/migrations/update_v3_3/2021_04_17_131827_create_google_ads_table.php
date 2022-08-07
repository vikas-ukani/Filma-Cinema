<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if ( !Schema::hasTable('google_ads') ) {
            Schema::create('google_ads', function (Blueprint $table) {
                $table->id();
                $table->string('google_ad_client',191);
                $table->string('google_ad_slot',191);
                $table->string('google_ad_width',191);
                $table->string('google_ad_height',191);
                $table->string('google_ad_starttime',191);
                $table->string('google_ad_endtime',191);
                $table->timestamps();
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
        Schema::dropIfExists('google_ads');
    }
}
