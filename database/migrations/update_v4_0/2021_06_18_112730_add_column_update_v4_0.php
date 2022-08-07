<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV40 extends Migration
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
                if (!Schema::hasColumn('app_configs', 'paystack_payment')){
                    $table->boolean('paystack_payment')->default(0);
                }
                
                if (!Schema::hasColumn('app_configs', 'remove_ads')){
                    $table->boolean('remove_ads')->default(0);
                }
                if (!Schema::hasColumn('app_configs', 'paytm_payment')){
                    $table->boolean('paytm_payment')->default(0);
                }
                 if (!Schema::hasColumn('app_configs', 'amazon_login')){
                    $table->boolean('amazon_login')->default(0);
                }
               
            });
        }
      
        if ( Schema::hasTable('movie_comments') ) {
            Schema::table('movie_comments', function (Blueprint $table) {
                if (!Schema::hasColumn('movie_comments', 'status')){
                    $table->boolean('status')->default(0);
                }
            });
        }
       
        if ( Schema::hasTable('movie_subcomments') ) {
            Schema::table('movie_subcomments', function (Blueprint $table) {
                if (!Schema::hasColumn('movie_subcomments', 'status')){
                    $table->boolean('status')->default(0);
                }
            });
        }

        if ( Schema::hasTable('buttons') ) {
            Schema::table('buttons', function (Blueprint $table) {
                if (!Schema::hasColumn('buttons', 'remove_ads')){
                    $table->boolean('remove_ads')->default(0);
                }
                if (!Schema::hasColumn('buttons', 'is_toprated')){
                    $table->boolean('is_toprated')->default(0);
                }
                if (!Schema::hasColumn('buttons', 'toprated_count')){
                    $table->text('toprated_count')->nullable();
                }
            });
        }
       
        if ( Schema::hasTable('configs') ) {
            Schema::table('configs', function (Blueprint $table) {
             
                if (!Schema::hasColumn('configs', 'comments_approval')){
                    $table->boolean('comments_approval')->default(1);
                }
            });
        }
         if ( Schema::hasTable('packages') ) {
            Schema::table('packages', function (Blueprint $table) {
                 if (!Schema::hasColumn('packages', 'feature')){
                    $table->longtext('feature')->nullable();
                }
                if (!Schema::hasColumn('packages', 'ads_in_web')){
                    $table->boolean('ads_in_web')->default(0);
                }
                if (!Schema::hasColumn('packages', 'ads_in_app')){
                    $table->boolean('ads_in_app')->default(0);
                }
            });
        }
        if ( Schema::hasTable('movies') ) {
            Schema::table('movies', function (Blueprint $table) {
                if (!Schema::hasColumn('movies', 'is_custom_label')){
                    $table->boolean('is_custom_label')->default(0);
                }
                if (!Schema::hasColumn('movies', 'label_id')){
                    $table->integer('label_id')->nullable();
                }
                if (!Schema::hasColumn('movies', 'upcoming_date')){
                    $table->string('upcoming_date')->nullable();
                }
            });
        }
        if ( Schema::hasTable('tv_series') ) {
            Schema::table('tv_series', function (Blueprint $table) {
                if (!Schema::hasColumn('tv_series', 'is_custom_label')){
                    $table->boolean('is_custom_label')->default(0);
                }
                if (!Schema::hasColumn('tv_series', 'label_id')){
                    $table->integer('label_id')->nullable();
                }
                if (!Schema::hasColumn('tv_series', 'is_upcoming')){
                   $table->boolean('is_upcoming')->default(0);
                }
                 if (!Schema::hasColumn('tv_series', 'upcoming_date')){
                    $table->integer('upcoming_date')->nullable();
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
                $table->dropColumn('paystack_payment');
                $table->dropColumn('paytm_payment');
                $table->dropColumn('remove_ads');
            });
        }
         if(Schema::hasTable('configs')){
            Schema::table('configs', function (Blueprint $table) {
                $table->dropColumn('comments_approval');
            });
        }
         if(Schema::hasTable('packages')){
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('feature');
                $table->dropColumn('ads_in_web');
                $table->dropColumn('ads_in_web');
            });
        }
         if(Schema::hasTable('movies')){
            Schema::table('movies', function (Blueprint $table) {
                $table->dropColumn('is_custom_label');
                $table->dropColumn('label_id');
                $table->dropColumn('upcoming_date');
            });
        }
         if(Schema::hasTable('tv_series')){
            Schema::table('tv_series', function (Blueprint $table) {
                $table->dropColumn('is_custom_label');
                $table->dropColumn('label_id');
                $table->dropColumn('is_upcoming');
                $table->dropColumn('upcoming_date');
            });
        }

         if(Schema::hasTable('movie_subcomments')){
            Schema::table('movie_subcomments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
         if(Schema::hasTable('movie_comments')){
            Schema::table('movie_comments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

         if(Schema::hasTable('buttons')){
            Schema::table('buttons', function (Blueprint $table) {
                $table->dropColumn('remove_ads');
                $table->dropColumn('is_toprated');
                $table->dropColumn('toprated_count');
            });
        }
    }
}
