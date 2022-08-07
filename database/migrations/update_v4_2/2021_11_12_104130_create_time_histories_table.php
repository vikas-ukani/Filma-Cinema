<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('time_histories') ) {
            Schema::create('time_histories', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('movie_id')->nullable();
                $table->integer('tv_id')->nullable();
                $table->integer('episode_id')->nullable();
                $table->text('file');
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
        Schema::dropIfExists('time_histories');
    }
}
