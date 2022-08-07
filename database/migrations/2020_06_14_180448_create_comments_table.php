<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('comments') ) {
			Schema::create('comments', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->string('name', 30);
				$table->integer('user_id');
				$table->string('email', 191);
				$table->integer('blog_id');
				$table->string('comment');
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
		Schema::drop('comments');
	}

}
