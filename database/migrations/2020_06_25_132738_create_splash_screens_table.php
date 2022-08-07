<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSplashScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('splash_screens') ) {
            Schema::create('splash_screens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('image', 191)->nullable();
                $table->boolean('logo_enable')->default(1);
                $table->string('logo',191)->nullable();
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
        Schema::dropIfExists('splash_screens');
    }
}
