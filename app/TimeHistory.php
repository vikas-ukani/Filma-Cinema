<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeHistory extends Model
{
    protected $table = 'time_histories';

    protected $fillable = [
        'user_id',
        'movie_id',
        'tv_id',
        'episode_id',
        'file',
    ];

}
