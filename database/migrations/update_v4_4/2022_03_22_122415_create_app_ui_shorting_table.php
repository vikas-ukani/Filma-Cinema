<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUiShortingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('app_ui_shortings') ) {
            Schema::create('app_ui_shortings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 191);
                $table->boolean('is_active')->default(1);
                $table->bigInteger('position');
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
        Schema::dropIfExists('app_ui_shortings');
    }
}
