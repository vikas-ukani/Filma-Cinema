<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerAddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('banner_adds') ) {
			Schema::create('banner_adds', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('image');
				$table->string('link', 1000);
                $table->integer('position');
				$table->boolean('column')->default(0);
				$table->boolean('is_active')->default(0);
                $table->boolean('detail_page')->default(0);
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
        Schema::dropIfExists('banner_adds');
    }
}
