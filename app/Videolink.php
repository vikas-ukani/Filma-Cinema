<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Videolink extends Model
{
    protected $fillable = [
        'movie_id',
        'episode_id',
        'iframeurl',
        'ready_url',
        'type',
        'url_360',
        'url_480',
        'url_720',
        'url_1080',
        'upload_video',
    ];
}
