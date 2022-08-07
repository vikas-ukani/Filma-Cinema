<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubcommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('subcomments') ) {
			Schema::create('subcomments', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('user_id');
				$table->integer('blog_id');
				$table->integer('comment_id');
				$table->string('reply');
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
		Schema::drop('subcomments');
	}

}
