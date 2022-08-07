<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnUpdateV34 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if(Schema::hasTable('users')){
            Schema::table('users', function (Blueprint $table) {
                 if (Schema::hasColumn('users','two_factor_code')){
                    $table->dropColumn('two_factor_code');
                
                 }
                if (Schema::hasColumn('users','two_factor_expires_at')){
                    $table->dropColumn('two_factor_expires_at');
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
        //
    }
}
