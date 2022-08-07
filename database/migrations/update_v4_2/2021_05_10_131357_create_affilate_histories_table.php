<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffilateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('affilate_histories')){
            Schema::create('affilate_histories', function (Blueprint $table) {
                $table->id();
                $table->integer('refer_user_id')->unsigned();
                $table->longText('log')->nullable();
                $table->integer('user_id')->unsigned();
                $table->double('amount')->default(0);
                $table->integer('procces')->unsigned();
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
        Schema::dropIfExists('affilate_histories');
    }
}
