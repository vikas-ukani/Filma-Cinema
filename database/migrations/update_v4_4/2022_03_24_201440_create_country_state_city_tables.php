<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryStateCityTables extends Migration
{
    public function up()
    {
        if ( !Schema::hasTable('countries') ) {
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('sortname');
                $table->string('phonecode');
                $table->timestamps();
            });
        }
        if ( !Schema::hasTable('states') ) {
            Schema::create('states', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('country_id');            
                $table->timestamps();
            });
        }
        if ( !Schema::hasTable('cities') ) {
            Schema::create('cities', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('state_id');            
                $table->timestamps();
            });
        }
    }
   public function down()
    {
       Schema::drop('countries');
       Schema::drop('states');
       Schema::drop('cities');
    }
}
