<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('app_sliders') ) {
            Schema::create('app_sliders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('movie_id')->unsigned()->nullable()->index('app_sliders_movie_id_foreign');
                $table->integer('tv_series_id')->unsigned()->nullable()->index('app_sliders_tv_series_id_foreign');
                $table->string('slide_image', 191)->nullable();
                $table->boolean('active')->default(0);
                $table->integer('position');
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
        Schema::dropIfExists('app_sliders');
    }
}
