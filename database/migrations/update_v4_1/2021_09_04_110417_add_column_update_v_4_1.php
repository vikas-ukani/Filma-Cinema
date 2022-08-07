<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV41 extends Migration
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
                if (!Schema::hasColumn('configs', 'payhere_payment')){
                    $table->boolean('payhere_payment')->default(0);
                }
                if (!Schema::hasColumn('configs', 'preloader_img')){
                    $table->longtext('preloader_img')->nullable();
                }
            });
        }
        if ( Schema::hasTable('app_configs') ) {
            Schema::table('app_configs', function (Blueprint $table) {
                if (!Schema::hasColumn('app_configs', 'is_admob')){
                    $table->boolean('is_admob')->default(0);
                }
                if (!Schema::hasColumn('app_configs', 'instamojo_payment')){
                    $table->boolean('instamojo_payment')->default(0);
                }
            });
        }
        if ( Schema::hasTable('blogs') ) {
            Schema::table('blogs', function (Blueprint $table) {
                if (!Schema::hasColumn('blogs', 'poster')){
                    $table->longText('poster')->nullable();
                }
            });
        }

        if ( Schema::hasTable('packages') ) {
            Schema::table('packages', function (Blueprint $table) {
                if (Schema::hasColumn('packages', 'status')){
                    $table->string('status',191)->default('active')->change();
                }
            });
        }
        if ( Schema::hasTable('buttons') ) {
            Schema::table('buttons', function (Blueprint $table) {
                if (!Schema::hasColumn('buttons', 'remove_thumbnail')){
                    $table->boolean('remove_thumbnail')->default(0);
                }
            });
        }

        if ( Schema::hasTable('actors') ) {
            Schema::table('actors', function (Blueprint $table) {
                if (!Schema::hasColumn('actors', 'slug')){
                    $table->longText('slug')->nullable();
                }
            });
        }

        if ( Schema::hasTable('directors') ) {
            Schema::table('directors', function (Blueprint $table) {
                if (!Schema::hasColumn('directors', 'slug')){
                    $table->longText('slug')->nullable();
                }
            });
        }

        if ( Schema::hasTable('languages') ) {
            Schema::table('languages', function (Blueprint $table) {
                if (!Schema::hasColumn('languages', 'rtl')){
                    $table->boolean('rtl')->default(0);
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
                $table->dropColumn('payhere_payment');
                $table->dropColumn('preloader_img');
            });
        }
        if(Schema::hasTable('app_configs')){
            Schema::table('app_configs', function (Blueprint $table) {
                $table->dropColumn('is_admob');
                $table->dropColumn('instamojo_payment');
            });
        }
        if(Schema::hasTable('blogs')){
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('poster');
            });
        }
        if(Schema::hasTable('actors')){
            Schema::table('actors', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
        if(Schema::hasTable('directors')){
            Schema::table('directors', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
        if(Schema::hasTable('languages')){
            Schema::table('languages', function (Blueprint $table) {
                $table->dropColumn('rtl');
            });
        }
    }
}
