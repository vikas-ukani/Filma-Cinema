<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerSetting extends Model
{
    protected $fillable = [
        'logo_enable', 'logo',
        'cpy_text',
        'share_opt',
        'auto_play',
        'speed',
        'thumbnail',
        'info_window',
        'skin',
        'loop_video',
        'is_resume',
        'player_google_analytics_id',
        'subtitle_font_size',
        'subtitle_color',
        'chromecast',

    ];
}
