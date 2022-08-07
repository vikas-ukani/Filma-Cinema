<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateViewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( !Schema::hasTable('views') ) {
			Schema::create('views', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('viewable_type', 191);
				$table->bigInteger('viewable_id')->unsigned();
				$table->text('visitor', 65535)->nullable();
				$table->string('collection', 191)->nullable();
				$table->timestamp('viewed_at')->default(DB::raw('CURRENT_TIMESTAMP'));
				$table->index(['viewable_type','viewable_id']);
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
		Schema::drop('views');
	}

}
