<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubtitlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('subtitles') ) {
			Schema::create('subtitles', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('sub_lang', 100)->nullable();
				$table->string('sub_t', 191)->nullable();
				$table->string('m_t_id', 100)->nullable();
				$table->integer('ep_id')->unsigned()->nullable();
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
		Schema::drop('subtitles');
	}

}
