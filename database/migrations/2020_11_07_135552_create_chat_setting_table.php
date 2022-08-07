<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('chat_settings') ) {
            Schema::create('chat_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key',191);
                $table->boolean('enable_messanger')->default(0);
                $table->longtext('script')->nullable();
                $table->string('mobile',191)->nullable();
                $table->string('text',191)->nullable();
                $table->string('header',191)->nullable();
                $table->string('color',191)->nullable();
                $table->integer('size')->nullable();
                $table->boolean('enable_whatsapp')->default(0);
                $table->string('position',191)->default('right');
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
        Schema::dropIfExists('chat_settings');
    }
}
