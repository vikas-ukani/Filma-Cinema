<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if ( !Schema::hasTable('app_configs') ) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('logo', 191)->nullable();
                $table->string('title', 191)->nullable();
                $table->boolean('stripe_payment')->default(0);
                $table->boolean('paypal_payment')->default(0);
                $table->boolean('razorpay_payment')->default(0);
                $table->boolean('brainetree_payment')->default(0);
                $table->boolean('paystack_payment')->default(0);
                $table->boolean('bankdetails')->default(0);
                $table->boolean('fb_check')->default(0);
                $table->boolean('google_login')->default(0);
                $table->boolean('amazon_lab_check')->default(0);
                $table->boolean('git_lab_check')->default(0);
                $table->string('ADMOB_APP_KEY',191)->nullable();
                $table->boolean('banner_admob')->default(0);
                $table->string('banner_id',191)->nullable();
                $table->boolean('interstitial_admob')->default(0);
                $table->string('interstitial_id',191)->nullable();
                $table->boolean('rewarded_admob')->default(0);
                $table->string('rewarded_id',191)->nullable();
                $table->boolean('native_admob')->default(0);
                $table->string('native_id',191)->nullable();
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
        Schema::dropIfExists('app_configs');
    }
}
