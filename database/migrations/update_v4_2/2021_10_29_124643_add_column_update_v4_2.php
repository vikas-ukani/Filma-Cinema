<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV42 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('app_configs') ) {
            Schema::table('app_configs', function (Blueprint $table) {
                if (!Schema::hasColumn('app_configs', 'banner_admob')){
                    $table->boolean('banner_admob')->default(0);
                }
                if (!Schema::hasColumn('app_configs', 'banner_id')){
                    $table->string('banner_id',191)->nullable();
                }
                if (!Schema::hasColumn('app_configs', 'interstitial_admob')){
                    $table->boolean('interstitial_admob')->default(0);
                }
                if (!Schema::hasColumn('app_configs', 'interstitial_id')){
                    $table->string('interstitial_id',191)->nullable();
                }
                if (!Schema::hasColumn('app_configs', 'generate_apikey')){
                    $table->text('generate_apikey')->nullable();
                }

            });
        }

        if ( Schema::hasTable('buttons') ) {
            Schema::table('buttons', function (Blueprint $table) {
                if (!Schema::hasColumn('buttons', 'reminder_mail')){
                    $table->boolean('reminder_mail')->default(0);
                }
               

            });
        }
        if ( Schema::hasTable('movies') ) {
            Schema::table('movies', function (Blueprint $table) {
                if (!Schema::hasColumn('movies', 'password')){
                    $table->text('password')->change();
                }
               

            });
        }
        if ( Schema::hasTable('seasons') ) {
            Schema::table('seasons', function (Blueprint $table) {
                if (!Schema::hasColumn('seasons', 'password')){
                    $table->text('password')->change();
                }

            });
        }

        if (Schema::hasTable('users')) {

            Schema::table('users', function (Blueprint $table) {

                if (!Schema::hasColumn('users', 'refer_code')) {

                    $table->string('refer_code')->nullable();

                }

                if (!Schema::hasColumn('users', 'refered_from')) {

                    $table->string('refered_from')->nullable();

                }
                if (!Schema::hasColumn('users', 'facebook_url')) {

                    $table->string('facebook_url')->nullable();

                }
                if (!Schema::hasColumn('users', 'youtube_url')) {

                    $table->string('youtube_url')->nullable();

                }
                if (!Schema::hasColumn('users', 'twitter_url')) {

                    $table->string('twitter_url')->nullable();

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
        if(Schema::hasTable('app_configs')){
            Schema::table('app_configs', function (Blueprint $table) {
                $table->dropColumn('banner_admob');
                $table->dropColumn('banner_id');
                $table->dropColumn('interstitial_admob');
                $table->dropColumn('interstitial_id');
                $table->dropColumn('generate_apikey');
            });
        }

        if(Schema::hasTable('buttons')){
            Schema::table('buttons', function (Blueprint $table) {
                $table->dropColumn('reminder_mail');
                
            });
        }

        if(Schema::hasTable('users')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('refer_code');
                $table->dropColumn('refered_from');
                $table->dropColumn('facebook_url');
                $table->dropColumn('youtube_url');
                $table->dropColumn('twitter_url');
                
            });
        }

    }
}
