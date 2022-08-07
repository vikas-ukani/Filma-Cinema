<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('ads') ) {
			Schema::create('ads', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->string('ad_type', 100);
				$table->string('ad_image', 100);
				$table->string('ad_video', 100);
				$table->string('ad_url', 100)->nullable();
				$table->string('ad_location', 100);
				$table->string('ad_target', 100)->nullable();
				$table->integer('ad_hold')->nullable();
				$table->string('time', 100);
				$table->string('endtime', 100)->nullable();
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
		Schema::drop('ads');
	}

}
