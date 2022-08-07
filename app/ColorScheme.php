<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    protected $fillable = [
        'color_scheme', 'default_navigation_color', 'custom_navigation_color', 'default_text_color', 'custom_text_color', 'default_text_on_color', 'custom_text_on_color', 'default_back_to_top_color', 'custom_back_to_top_color', 'default_footer_background_color', 'custom_footer_background_color', 'default_back_to_top_bgcolor', 'custom_back_to_top_bgcolor', 'default_back_to_top_bgcolor_on_hover', 'custom_back_to_top_bgcolor_on_hover', 'default_back_to_top_color_on_hover', 'custom_back_to_top_color_on_hover',
    ];
}
