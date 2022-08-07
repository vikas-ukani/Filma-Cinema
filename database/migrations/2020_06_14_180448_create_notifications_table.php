<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('notifications') ) {
			
			Schema::create('notifications', function (Blueprint $table) {
	            $table->uuid('id')->primary();
	            $table->string('type');
	            $table->longtext('title')->nullable();
	            $table->morphs('notifiable');
	            $table->text('data');
	            $table->integer('movie_id')->nullable();
				$table->integer('tv_id')->nullable();
	            $table->timestamp('read_at')->nullable();
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
		Schema::drop('notifications');
	}

}
