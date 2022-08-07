<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLiveEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('live_events') ) {
			Schema::create('live_events', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->string('title');
				$table->string('slug');
				$table->text('description');
				$table->string('type', 191);
				$table->text('iframeurl')->nullable();
				$table->text('ready_url')->nullable();
				$table->dateTime('start_time');
				$table->dateTime('end_time');
				$table->boolean('status');
				$table->text('thumbnail', 65535)->nullable();
				$table->text('poster', 65535)->nullable();
				$table->text('organized_by', 65535)->nullable();
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
		Schema::drop('live_events');
	}

}
