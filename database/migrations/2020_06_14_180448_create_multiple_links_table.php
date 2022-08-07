<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMultipleLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('multiple_links') ) {
			Schema::create('multiple_links', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('movie_id')->nullable();
				$table->integer('episode_id')->nullable();
				$table->boolean('download');
				$table->string('quality', 191);
				$table->string('size', 191);
				$table->string('language', 191);
				$table->string('url');
				$table->integer('clicks')->default(0);
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
		Schema::drop('multiple_links');
	}

}
