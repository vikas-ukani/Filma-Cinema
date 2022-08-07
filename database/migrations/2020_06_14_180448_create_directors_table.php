<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDirectorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('directors') ) {
			Schema::create('directors', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('name', 191);
				$table->string('image', 191)->nullable();
				$table->text('biography')->nullable();
				$table->text('place_of_birth', 16777215)->nullable();
				$table->date('DOB')->nullable();
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
		Schema::drop('directors');
	}

}
