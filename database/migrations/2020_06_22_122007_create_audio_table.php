<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('audio') ) {
            Schema::create('audio', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title', 191);
                $table->string('slug', 191)->nullable();
                $table->text('keyword')->nullable();
                $table->text('description')->nullable();
                $table->string('thumbnail', 191)->nullable();
                $table->string('poster', 191)->nullable();
                $table->float('rating')->nullable();
                $table->string('maturity_rating', 191)->nullable();
                $table->string('upload_audio', 100)->nullable();
                $table->string('type', 100)->nullable();
                $table->string('genre_id', 100)->nullable();
                $table->text('detail')->nullable();
                $table->boolean('is_protect')->default(0);
                $table->string('password', 191)->nullable();
                $table->string('audiourl', 191)->nullable();
                $table->boolean('featured')->nullable();
                $table->boolean('status')->nullable();
              
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
        Schema::dropIfExists('audio');
    }
}
