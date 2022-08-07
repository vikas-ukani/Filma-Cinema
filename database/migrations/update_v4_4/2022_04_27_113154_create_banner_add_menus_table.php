<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerAddMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('banner_add_menus') ) {
			Schema::create('banner_add_menus', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('menu_id');
				$table->integer('banneradd_id')->unsigned();
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
        Schema::dropIfExists('banner_add_menus');
    }
}
