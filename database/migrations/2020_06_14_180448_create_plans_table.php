<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('plans') ) {
			Schema::create('plans', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('name', 191);
				$table->string('image', 191)->nullable();
				$table->string('email', 191)->unique();
				$table->string('password', 191);
				$table->string('plan', 191)->nullable();
				$table->date('dob')->nullable();
				$table->string('mobile', 191)->nullable();
				$table->string('stripe_id', 191)->nullable();
				$table->string('card_brand', 191)->nullable();
				$table->string('card_last_four', 191)->nullable();
				$table->dateTime('trial_ends_at')->nullable();
				$table->boolean('is_admin')->default(0);
				$table->string('remember_token', 100)->nullable();
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
		Schema::drop('plans');
	}

}
