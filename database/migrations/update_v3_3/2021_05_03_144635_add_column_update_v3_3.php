<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV33 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('configs') ) {
            Schema::table('configs', function (Blueprint $table) {
                if (!Schema::hasColumn('configs', 'aws')){
                    $table->boolean('aws')->default(0);
                }
                if (!Schema::hasColumn('configs', 'omise_payment')){
                    $table->boolean('omise_payment')->default(0);
                }
                 if (!Schema::hasColumn('configs', 'flutterrave_payment')){
                    $table->boolean('flutterrave_payment')->default(0);
                }
            });
        }
        if ( Schema::hasTable('buttons') ) {
            Schema::table('buttons', function (Blueprint $table) {
                if (!Schema::hasColumn('buttons', 'two_factor')){
                    $table->boolean('two_factor')->default(0);
                }
                if (!Schema::hasColumn('buttons', 'countviews')){
                    $table->boolean('countviews')->default(0);
                }
            });
        }
        if ( Schema::hasTable('movies') ) {
            Schema::table('movies', function (Blueprint $table) {
                if (!Schema::hasColumn('movies', 'is_upcoming')){
                    $table->boolean('is_upcoming')->default(0);
                }
            });
        }
        if ( Schema::hasTable('app_configs') ) {
            Schema::table('app_configs', function (Blueprint $table) {
                if (!Schema::hasColumn('app_configs', 'inapp_payment')){
                    $table->boolean('inapp_payment')->default(0);
                }
                 if (!Schema::hasColumn('app_configs', 'push_key')){
                    $table->boolean('push_key')->default(0);
                }
            });
        }
        if(Schema::hasTable('users')){
            Schema::table('users', function (Blueprint $table) {
                 if (!Schema::hasColumn('users','two_factor_code')){
                    $table->string('two_factor_code')->nullable();
                 }
                if (!Schema::hasColumn('users','two_factor_expires_at')){
                    $table->dateTime('two_factor_expires_at')->nullable();
                }
            });
        }

         if ( Schema::hasTable('audio_languages') ) {
            Schema::table('audio_languages', function (Blueprint $table) {
                if (!Schema::hasColumn('audio_languages', 'image')){
                    $table->string('image')->nullable();
                }
                if (!Schema::hasColumn('audio_languages', 'status')){
                    $table->boolean('status')->default(0);
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
       if(Schema::hasTable('configs')){
            Schema::table('configs', function (Blueprint $table) {
                $table->dropColumn('aws');
                $table->dropColumn('omise_payment');
                $table->dropColumn('flutterrave_payment');
            });
        }
        if(Schema::hasTable('audio_languages')){
            Schema::table('audio_languages', function (Blueprint $table) {
                $table->dropColumn('image');
                $table->dropColumn('status');
               
            });
        }
         if(Schema::hasTable('buttons')){
            Schema::table('buttons', function (Blueprint $table) {
                $table->dropColumn('two_factor');
                $table->dropColumn('countviews');
            });
        }

        if(Schema::hasTable('movies')){
            Schema::table('movies', function (Blueprint $table) {
                $table->dropColumn('is_upcoming');
            });
        }
         if(Schema::hasTable('app_configs')){
            Schema::table('app_configs', function (Blueprint $table) {
                $table->dropColumn('inapp_payment');
                $table->dropColumn('push_key');
            });
        }

         if(Schema::hasTable('users')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('two_factor_code');
                $table->dropColumn('two_factor_expires_at');
            });
        }
    }
}
