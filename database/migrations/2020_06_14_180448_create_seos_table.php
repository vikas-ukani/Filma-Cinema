<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('seos') ) {
			Schema::create('seos', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('author', 191)->nullable();
				$table->text('fb', 65535)->nullable();
				$table->text('google', 65535)->nullable();
				$table->text('metadata', 65535)->nullable();
				$table->text('description');
				$table->text('keyword');
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
		Schema::drop('seos');
	}

}
