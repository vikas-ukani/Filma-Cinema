<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpsCppTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('cps_cpp') ) {
			Schema::create('cps_cpp', function(Blueprint $table)
			{
				$table->integer('userid');
				$table->integer('expire');
				$table->text('info', 65535);
				$table->integer('plan');
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
		Schema::drop('cps_cpp');
	}

}
