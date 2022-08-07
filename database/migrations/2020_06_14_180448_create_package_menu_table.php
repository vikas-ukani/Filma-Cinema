<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackageMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('package_menu') ) {
			Schema::create('package_menu', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('menu_id');
				$table->integer('pkg_id');
				$table->string('package_id', 200);
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
		Schema::drop('package_menu');
	}

}
