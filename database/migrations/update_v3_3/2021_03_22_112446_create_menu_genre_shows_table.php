<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuGenreShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('menu_genre_shows') ) {
            Schema::create('menu_genre_shows', function (Blueprint $table) {
                $table->id();
                $table->integer('menu_id')->unsigned();
                $table->integer('menu_section_id')->unsigned();
                $table->integer('genre_id')->unsigned();
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
        Schema::dropIfExists('menu_genre_shows');
    }
}
