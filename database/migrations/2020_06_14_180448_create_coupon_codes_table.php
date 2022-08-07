<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCouponCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('coupon_codes') ) {
			Schema::create('coupon_codes', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('coupon_code', 191);
				$table->float('percent_off')->nullable();
				$table->string('currency', 191);
				$table->float('amount_off')->nullable();
				$table->string('duration', 191)->default('once');
				$table->string('max_redemptions', 191)->nullable();
				$table->dateTime('redeem_by')->nullable();
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
		Schema::drop('coupon_codes');
	}

}
