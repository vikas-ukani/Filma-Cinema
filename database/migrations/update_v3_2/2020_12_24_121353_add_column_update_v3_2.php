<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV32 extends Migration
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
                if (!Schema::hasColumn('configs', 'mollie_payment')){
                    $table->boolean('mollie_payment')->default(0);
                }
            });
        }

         if ( Schema::hasTable('configs') ) {
            Schema::table('configs', function (Blueprint $table) {
                if (!Schema::hasColumn('configs', 'cashfree_payment')){
                    $table->boolean('cashfree_payment')->default(0);
                }
            });
        }


        if ( Schema::hasTable('buttons') ) {
            Schema::table('buttons', function (Blueprint $table) {
                if (!Schema::hasColumn('buttons', 'multiplescreen')){
                    $table->boolean('multiplescreen')->default(0);
                }
            });
        }

        if ( Schema::hasTable('coupon_codes') ) {
            Schema::table('coupon_codes', function (Blueprint $table) {
                if (!Schema::hasColumn('coupon_codes', 'in_stripe')){
                    $table->boolean('in_stripe')->default(0);
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
                $table->dropColumn('mollie_payment');
            });
        }
         if(Schema::hasTable('configs')){
            Schema::table('configs', function (Blueprint $table) {
                $table->dropColumn('cashfree_payment');
            });
        }

        if(Schema::hasTable('buttons')){
            Schema::table('buttons', function (Blueprint $table) {
                $table->dropColumn('multiplescreen');
            });
        }

         if(Schema::hasTable('coupon_codes')){
            Schema::table('coupon_codes', function (Blueprint $table) {
                $table->dropColumn('in_stripe');
            });
        }
    }
}
