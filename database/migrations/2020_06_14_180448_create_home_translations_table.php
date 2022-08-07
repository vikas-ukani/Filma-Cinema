<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('home_translations') ) {
			Schema::create('home_translations', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('key', 191)->nullable();
				$table->text('value')->nullable();
				$table->integer('status')->default(1);
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
		Schema::drop('home_translations');
	}

}
