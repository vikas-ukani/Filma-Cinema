<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('packages') ) {
			Schema::create('packages', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('plan_id', 191);
				$table->string('name', 191);
				$table->string('currency', 191);
				$table->string('currency_symbol', 50);
				$table->float('amount');
				$table->string('interval', 191);
				$table->integer('interval_count')->nullable();
				$table->string('trial_period_days', 191)->nullable();
				$table->string('status',191)->default('active');
				$table->integer('screens')->unsigned()->default(1);
				$table->boolean('download')->default(0);
				$table->integer('downloadlimit')->nullable();
				$table->integer('delete_status')->default(1);
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
		Schema::drop('packages');
	}

}
