<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEpisodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('episodes') ) {
			Schema::table('episodes', function(Blueprint $table)
			{
				$table->foreign('seasons_id')->references('id')->on('seasons')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('episodes', function(Blueprint $table)
		{
			$table->dropForeign('episodes_seasons_id_foreign');
		});
	}

}
