<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('manual_payments') ) {
            Schema::create('manual_payments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->string('payment_id', 191)->nullable();
                $table->string('user_name', 191)->nullable();
                $table->integer('package_id')->nullable();
                $table->float('price');
                $table->boolean('status');
                $table->string('method', 191);
                $table->string('file', 191)->nullable();
                $table->dateTime('subscription_from')->nullable();
                $table->dateTime('subscription_to')->nullable();
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
        Schema::dropIfExists('manual_payments');
    }
}
