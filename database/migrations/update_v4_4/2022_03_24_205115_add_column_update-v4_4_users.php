<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUpdateV44Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('users') ) {
            Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'address')){
                $table->string('address',250)->nullable();
            }
            if (!Schema::hasColumn('users', 'country')){
                $table->string('country',11)->nullable();
            }
            if (!Schema::hasColumn('users', 'state')){
                $table->string('state',11)->nullable();
            }
            if (!Schema::hasColumn('city', 'city')){
                $table->string('city',11)->nullable();
            }
            if (!Schema::hasColumn('users', 'kids_mode_active')){
                $table->boolean('kids_mode_active')->default(0);
            }
            });
        }
        if ( Schema::hasTable('menu_sections') ) {
            Schema::table('menu_sections', function (Blueprint $table) {
                if (!Schema::hasColumn('menu_sections', 'position')){
                    $table->bigInteger('position');
                }
            });
        }
        if ( Schema::hasTable('packages') ) {
            Schema::table('packages', function (Blueprint $table) {
                if (!Schema::hasColumn('packages', 'position')){
                    $table->bigInteger('position');
                }
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
        //
    }
}
