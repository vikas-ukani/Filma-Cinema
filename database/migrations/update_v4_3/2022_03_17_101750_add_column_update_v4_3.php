<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV43 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('movies') ) {
            Schema::table('movies', function (Blueprint $table) {
                if (Schema::hasColumn('movies', 'password')){
                    $table->longText('password')->change();
                }
               

            });
        }
        if ( Schema::hasTable('seasons') ) {
            Schema::table('seasons', function (Blueprint $table) {
                if (Schema::hasColumn('seasons', 'password')){
                    $table->longText('password')->change();
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
