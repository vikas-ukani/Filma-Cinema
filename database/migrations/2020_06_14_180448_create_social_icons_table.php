<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialIconsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('social_icons') ) {
			Schema::create('social_icons', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->string('url1', 191)->nullable();
				$table->string('url2', 191)->nullable();
				$table->string('url3', 191)->nullable();
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
		Schema::drop('social_icons');
	}

}
