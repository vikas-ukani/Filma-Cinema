<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('reminder_mails') ) {
            Schema::create('reminder_mails', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->integer('subscription_id')->unsigned();
                $table->integer('today')->nullable();
                $table->integer('before_7day')->nullable();
                $table->integer('after_7day')->nullable();
            });
        }

        if ( !Schema::hasTable('app_sliders') ) {
            Schema::create('app_sliders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('movie_id')->unsigned()->nullable()->index('app_sliders_movie_id_foreign');
                $table->integer('tv_series_id')->unsigned()->nullable()->index('app_sliders_tv_series_id_foreign');
                $table->string('slide_image', 191)->nullable();
                $table->boolean('active')->default(0);
                $table->integer('position');
                $table->timestamps();
            });
        }

         if ( !Schema::hasTable('splash_screens') ) {
            Schema::create('splash_screens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('image', 191)->nullable();
                $table->boolean('logo')->default(1);
                $table->timestamps();
            });
        }

         if ( !Schema::hasTable('app_configs') ) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('logo', 191)->nullable();
                $table->string('title', 191)->nullable();
                $table->boolean('stripe_payment')->default(0);
                $table->boolean('paypal_payment')->default(0);
                $table->boolean('razorpay_payment')->default(0);
                $table->boolean('brainetree_payment')->default(0);
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

         if (!Schema::hasTable('manual_payments') ) {
            Schema::create('manual_payments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->string('payment_id', 191)->nullable();
                $table->string('user_name', 191)->nullable();
                $table->integer('package_id')->nullable();
                $table->float('price');
                $table->boolean('status');
                $table->string('method', 191);
                $table->string('file', 191)->nullable();
                $table->dateTime('subscription_from')->nullable();
                $table->dateTime('subscription_to')->nullable();
                $table->timestamps();
                
            });
        }

        if ( !Schema::hasTable('audio') ) {
            Schema::create('audio', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title', 191);
                $table->string('slug', 191)->nullable();
                $table->text('keyword')->nullable();
                $table->text('description')->nullable();
                $table->string('thumbnail', 191)->nullable();
                $table->string('poster', 191)->nullable();
                $table->float('rating')->nullable();
                $table->string('maturity_rating', 191)->nullable();
                $table->string('upload_audio', 100)->nullable();
                $table->string('type', 100)->nullable();
                $table->string('genre_id', 100)->nullable();
                $table->text('detail')->nullable();
                $table->boolean('is_protect');
                $table->string('password', 191)->nullable();
                $table->string('audiourl', 191)->nullable();
                $table->boolean('featured')->nullable();
                $table->boolean('status')->nullable();
              
                $table->timestamps();
            });
        }

        if ( !Schema::hasTable('live_events') ) {
            Schema::create('live_events', function(Blueprint $table)
            {
                $table->integer('id', true);
                $table->string('title');
                $table->string('slug');
                $table->text('description');
                $table->string('type', 191);
                $table->text('iframeurl')->nullable();
                $table->text('ready_url')->nullable();
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->boolean('status');
                $table->text('thumbnail', 65535)->nullable();
                $table->text('poster', 65535)->nullable();
                $table->text('organized_by', 65535)->nullable();
                $table->timestamps();
                
            });
        }

         if ( !Schema::hasTable('chat_settings') ) {
            Schema::create('chat_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key',191);
                $table->boolean('enable_messanger')->default(0);
                $table->longtext('script')->nullable();
                $table->string('mobile',191)->nullable();
                $table->string('text',191)->nullable();
                $table->string('header',191)->nullable();
                $table->string('color',191)->nullable();
                $table->integer('size')->nullable();
                $table->boolean('enable_whatsapp')->default(0);
                $table->boolean('position')->default(0);
                $table->timestamps();
            });
        }

        if ( !Schema::hasTable('subscription_items') ) {
            Schema::create('subscription_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('subscription_id');
                $table->string('stripe_id')->index();
                $table->string('stripe_plan');
                $table->integer('quantity')->nullable();
                $table->timestamps();
                $table->unique(['subscription_id', 'stripe_plan']);
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
        if(Schema::hasTable('reminder_mails')){
            Schema::dropIfExists('reminder_mails');
        }
        if(Schema::hasTable('app_sliders')){
         Schema::dropIfExists('app_sliders');
        }
        if(Schema::hasTable('splash_screens')){
            Schema::dropIfExists('splash_screens');
        }
        if(Schema::hasTable('app_configs')){
            Schema::dropIfExists('app_configs');
        }
        if(Schema::hasTable('manual_payments')){
            Schema::dropIfExists('manual_payments');
        }
        if(Schema::hasTable('audio')){
            Schema::dropIfExists('audio');
        }
        if(Schema::hasTable('live_events')){
             Schema::dropIfExists('live_events');
         }
        if(Schema::hasTable('chat_settings')){
             Schema::dropIfExists('chat_settings');
        }
        if(Schema::hasTable('subscription_items')){
             Schema::dropIfExists('subscription_items');
         }
    }
}
