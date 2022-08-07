<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricingTextsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('pricing_texts') ) {
			Schema::create('pricing_texts', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('package_id');
				$table->text('title1', 16777215)->nullable();
				$table->text('title2', 16777215)->nullable();
				$table->text('title3', 16777215)->nullable();
				$table->text('title4', 16777215)->nullable();
				$table->text('title5', 16777215)->nullable();
				$table->text('title6', 16777215)->nullable();
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
		Schema::drop('pricing_texts');
	}

}
