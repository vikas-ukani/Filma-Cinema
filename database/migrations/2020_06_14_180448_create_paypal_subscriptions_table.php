<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaypalSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('paypal_subscriptions') ) {
			Schema::create('paypal_subscriptions', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id')->unsigned();
				$table->string('payment_id', 191)->nullable();
				$table->string('user_name', 191)->nullable();
				$table->integer('package_id')->nullable();
				$table->float('price');
				$table->boolean('status');
				$table->string('method', 191);
				$table->dateTime('subscription_from')->nullable();
				$table->dateTime('subscription_to')->nullable();
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
		Schema::drop('paypal_subscriptions');
	}

}
