<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdate31Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('subscriptions') ) {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('subscriptions', 'stripe_status')){
                    $table->string('stripe_status')->nullable()->after('stripe_id');
                }
            });
        }
        if ( Schema::hasTable('notifications') ) {
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'title')){
                    $table->longtext('title')->nullable()->after('type');
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
         if(Schema::hasTable('subscriptions')){
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('stripe_status');
            });
        }
        if(Schema::hasTable('notifications')){
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }
}
