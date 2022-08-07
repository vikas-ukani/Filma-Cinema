<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('color_schemes') ) {
            Schema::create('color_schemes', function (Blueprint $table) {
                $table->id();
                $table->string('color_scheme');
                $table->text('default_navigation_color');
                $table->text('custom_navigation_color')->nullable();
                $table->text('default_text_color');
                $table->text('custom_text_color')->nullable();
                $table->text('default_text_on_color');
                $table->text('custom_text_on_color')->nullable();
                $table->text('default_back_to_top_color');
                $table->text('custom_back_to_top_color')->nullable();
                $table->text('default_back_to_top_bgcolor');
                $table->text('custom_back_to_top_bgcolor')->nullable();
                $table->text('default_back_to_top_bgcolor_on_hover');
                $table->text('custom_back_to_top_bgcolor_on_hover')->nullable();
                $table->text('default_back_to_top_color_on_hover');
                $table->text('custom_back_to_top_color_on_hover')->nullable();
                $table->text('default_footer_background_color');
                $table->text('custom_footer_background_color')->nullable();

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
        Schema::dropIfExists('color_schemes');
    }
}
