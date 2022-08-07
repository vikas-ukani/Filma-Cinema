<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('user_wallet_histories') ) {
            Schema::create('user_wallet_histories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('wallet_id')->unsigned();
                $table->string('type');
                $table->string('log')->nullable();
                $table->string('txn_id')->nullable();
                $table->float('amount', 10, 0)->nullable();
                $table->timestamps();
                $table->timestamp('expire_at')->nullable();
                $table->integer('expired')->default(0)->unsigned();
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
        Schema::dropIfExists('user_wallet_histories');
    }
}
