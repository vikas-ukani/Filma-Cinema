<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWishlistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('wishlists') ) {
			Schema::create('wishlists', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id')->unsigned()->index('wishlists_user_id_foreign');
				$table->integer('movie_id')->nullable();
				$table->integer('season_id')->nullable();
				$table->boolean('added')->default(0);
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
		Schema::drop('wishlists');
	}

}
