<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('auth_log') ) {
            Schema::create('auth_log', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('authenticatable');
                $table->string('ip_address', 45)->nullable();
                $table->text('platform')->nullable();
                $table->text('browser')->nullable();
                $table->timestamp('login_at')->nullable();
                $table->timestamp('logout_at')->nullable();
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
        Schema::dropIfExists('auth_log');
    }
}
