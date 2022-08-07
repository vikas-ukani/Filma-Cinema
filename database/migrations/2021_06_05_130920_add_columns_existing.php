<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsExisting extends Migration
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

        if ( Schema::hasTable('player_settings') ) {
            Schema::table('player_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('player_settings', 'chromecast')){
                    $table->boolean('chromecast')->nullable()->after('subtitle_color');
                }
            });
        }
        
        if ( Schema::hasTable('subscriptions') ) {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('subscriptions', 'subscription_from')){
                    $table->timestamp('subscription_from')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'subscription_to')){
                    $table->timestamp('subscription_to')->nullable();
                }
                if (!Schema::hasColumn('subscriptions', 'amount')){
                    $table->double('amount')->nullable();
                }
            });
        }

        if ( Schema::hasTable('genres') ) {
            Schema::table('genres', function (Blueprint $table) {
                if (!Schema::hasColumn('genres', 'image')){
                    $table->string('image', 191)->nullable()->after('name');
                }
            });
        }

        if ( Schema::hasTable('users') ) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'amazon_id')){
                    $table->string('amazon_id', 191)->nullable()->unique('amazon_id')->after('gitlab_id');
                }
            });
        }

       

        if ( Schema::hasTable('live_events') ) {
            Schema::table('live_events', function (Blueprint $table) {
                if (!Schema::hasColumn('live_events', 'genre_id')){
                    $table->string('genre_id', 191)->nullable()->after('poster');
                }
                if (!Schema::hasColumn('live_events','detail')){
                    $table->longtext('detail')->nullable()->after('genre_id');
                }
            });
        }

        if ( Schema::hasTable('configs') ) {
            Schema::table('configs', function (Blueprint $table) {
                if (!Schema::hasColumn('configs', 'captcha')){
                    $table->boolean('captcha')->default(0)->before('created_at');
                }
                if (!Schema::hasColumn('configs', 'amazon_login')){
                    $table->boolean('amazon_login')->default(0)->after('captcha');
                }
            });
        }

        if ( Schema::hasTable('movies') ) {
            Schema::table('movies', function (Blueprint $table) {
               if (!Schema::hasColumn('movies', 'slug')){
                    $table->string('slug', 191)->nullable()->after('title');
                }
                if (!Schema::hasColumn('movies', 'is_protect')){
                    $table->integer('is_protect')->default(0)->before('created_by');
                }
                if (!Schema::hasColumn('movies','password')){
                    $table->string('password',191)->nullable()->after('is_protect');
                }
               
            });
        }

        if ( Schema::hasTable('seasons') ) {
            Schema::table('seasons', function (Blueprint $table) {
                if (!Schema::hasColumn('seasons', 'season_slug')){
                    $table->string('season_slug', 191)->nullable()->after('season_no');
                }
                if (!Schema::hasColumn('seasons', 'is_protect')){
                    $table->integer('is_protect')->default(0)->after('type');
                }
                if (!Schema::hasColumn('seasons','password')){
                    $table->string('password',191)->nullable()->after('is_protect');
                }
                if (!Schema::hasColumn('seasons','trailer_url')){
                    $table->longtext('trailer_url',65535)->nullable()->after('type');
                }
            });
        }

        if ( Schema::hasTable('menu_videos') ) {
            Schema::table('menu_videos', function (Blueprint $table) {
                if (!Schema::hasColumn('menu_videos', 'live_event_id')){
                    $table->integer('live_event_id')->nullable()->after('tv_series_id');
                }
                if (!Schema::hasColumn('menu_videos', 'audio_id')){
                    $table->integer('audio_id')->nullable()->after('live_event_id');
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
                $table->dropColumn('comming_soon');
                $table->dropColumn('commingsoon_enabled_ip');
                $table->dropColumn('ip_block');
                $table->dropColumn('ipblock_ip');
                $table->dropColumn('maintenance');
                $table->dropColumn('comming_soon_text');
                $table->dropColumn('remove_subscription');
                $table->dropColumn('protip');
            });
        }

        if(Schema::hasTable('player_settings')){
            Schema::table('player_settings', function (Blueprint $table) {
                $table->dropColumn('chromecast');
            });
        }
        
        if(Schema::hasTable('subscriptions')){
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('subscription_from');
                $table->dropColumn('subscription_to');
                $table->dropColumn('amount');
            });
        }

        if(Schema::hasTable('genres')){
            Schema::table('genres', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

        if(Schema::hasTable('users')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('amazon_id');
            });
        }
       
        if(Schema::hasTable('live_events')){
            Schema::table('live_events', function (Blueprint $table) {
                $table->dropColumn('genre_id');
                $table->dropColumn('detail');
            });
        }

        if(Schema::hasTable('configs')){
            Schema::table('configs', function (Blueprint $table) {
                $table->dropColumn('captcha');
                $table->dropColumn('amazon_login');
            });
        }

        if(Schema::hasTable('movies')){
            Schema::table('movies', function (Blueprint $table) {
                $table->dropColumn('slug');
                $table->dropColumn('is_protect');
                $table->dropColumn('password');
            });
        }

        if(Schema::hasTable('seasons')){
            Schema::table('seasons', function (Blueprint $table) {
                $table->dropColumn('season_slug');
                $table->dropColumn('is_protect');
                $table->dropColumn('password');
                $table->dropColumn('trailer_url');
            });
         }

        if(Schema::hasTable('menu_videos')){
            Schema::table('menu_videos', function (Blueprint $table) {
                $table->dropColumn('live_event_id');
                $table->dropColumn('audio_id');
            });
        }
    }
}
