<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('users') ) {
			Schema::create('users', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('name', 191);
				$table->string('image', 191)->nullable();
				$table->string('email', 191)->unique();
				$table->string('verifyToken')->nullable();
				$table->boolean('status');
				$table->string('password', 191)->nullable();
				$table->string('google_id', 191)->nullable()->unique('google_id');
				$table->string('facebook_id', 191)->nullable()->unique('facebook_id');
				$table->string('gitlab_id', 191)->nullable()->unique('gitlab_id');
				$table->date('dob')->nullable();
				$table->integer('age')->nullable()->default(0);
				$table->string('mobile', 191)->nullable();
				$table->string('braintree_id', 191)->nullable();
				$table->string('code', 191)->nullable()->unique('code');
				$table->boolean('is_admin')->default(0);
				$table->integer('is_assistant')->unsigned()->default(0);
				$table->string('remember_token', 100)->nullable();
				$table->boolean('is_blocked')->nullable()->default(0);
				$table->string('amazon_id', 191)->nullable()->unique('amazon_id');
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
		Schema::drop('users');
	}

}
