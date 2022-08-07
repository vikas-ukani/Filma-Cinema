<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('blogs') ) {
			Schema::create('blogs', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('user_id');
				$table->string('title', 60);
				$table->string('slug', 191);
				$table->string('image');
				$table->string('detail', 5000);
				$table->integer('is_active');
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
		Schema::drop('blogs');
	}

}
