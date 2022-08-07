<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMultiplescreensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('multiplescreens') ) {
			Schema::create('multiplescreens', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('screen1', 50)->nullable();
				$table->string('screen2', 50)->nullable();
				$table->string('screen3', 50)->nullable();
				$table->string('screen4', 50)->nullable();
				$table->integer('user_id')->unsigned();
				$table->string('activescreen', 191)->nullable();
				$table->string('screen_1_used', 100)->default('NO');
				$table->string('screen_2_used', 100)->default('NO');
				$table->string('screen_3_used', 100)->default('NO');
				$table->string('screen_4_used', 100)->default('NO');
				$table->string('device_mac_1', 100)->nullable();
				$table->string('device_mac_2', 100)->nullable();
				$table->string('device_mac_3', 100)->nullable();
				$table->string('device_mac_4', 100)->nullable();
				$table->integer('download_1')->nullable();
				$table->integer('download_2')->nullable();
				$table->integer('download_3')->nullable();
				$table->integer('download_4')->nullable();
				$table->integer('pkg_id')->unsigned();
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
		Schema::drop('multiplescreens');
	}

}
