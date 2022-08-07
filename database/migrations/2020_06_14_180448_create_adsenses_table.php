<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdsensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('adsenses') ) {
			Schema::create('adsenses', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->text('code');
				$table->boolean('status');
				$table->boolean('ishome');
				$table->boolean('isviewall');
				$table->boolean('issearch');
				$table->boolean('iswishlist');
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
		Schema::drop('adsenses');
	}

}
