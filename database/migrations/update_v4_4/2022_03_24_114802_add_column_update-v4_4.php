<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV44 extends Migration
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
                if (!Schema::hasColumn('movies', 'views')){
                $table->integer('views')->default(0)->after('detail');
                }
            });
        }
        if ( Schema::hasTable('seasons') ) {
            Schema::table('seasons', function (Blueprint $table) {
                if (!Schema::hasColumn('seasons', 'views')){
                $table->integer('views')->default(0)->after('detail');
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
