<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDonaterListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('donater_lists') ) {
			Schema::create('donater_lists', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('user_id')->unsigned()->nullable();
				$table->string('method', 191)->nullable();
				$table->text('donor_msg')->nullable();
				$table->string('amount', 191)->nullable();
				$table->string('payment_id', 191)->nullable();
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
		Schema::drop('donater_lists');
	}

}
