<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('jobs') ) {
			Schema::create('jobs', function(Blueprint $table)
			{
				$table->bigInteger('id', true)->unsigned();
				$table->string('queue', 191)->index();
				$table->text('payload');
				$table->boolean('attempts');
				$table->integer('reserved_at')->unsigned()->nullable();
				$table->integer('available_at')->unsigned();
				$table->dateTime('created_at');
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
		Schema::drop('jobs');
	}

}
