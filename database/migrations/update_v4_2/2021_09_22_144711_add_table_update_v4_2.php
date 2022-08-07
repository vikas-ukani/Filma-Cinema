<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableUpdateV42 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('hide_for_me') ) {
            Schema::create('hide_for_me', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('movie_id')->nullable();
                $table->integer('season_id')->nullable();
                $table->longtext('profile')->nullable();
                $table->string('type',191);
                $table->timestamps();
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
        if (Schema::hasTable('hide_for_me') ) {
            Schema::dropIfExists('hide_for_me');
        }
    }
}
