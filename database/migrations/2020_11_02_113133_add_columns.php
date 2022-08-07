<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('genres') ) {
            Schema::table('genres', function (Blueprint $table) {
                if (!Schema::hasColumn('genres', 'image')){
                    $table->string('image', 191)->nullable()->after('name');
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

        


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         if(Schema::hasTable('genres')){
            Schema::table('genres', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }


         if(Schema::hasTable('live_events')){
            Schema::table('live_events', function (Blueprint $table) {
                $table->dropColumn('genre_id');
                $table->dropColumn('detail');
            });
        }
    }
}
