<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{

    protected $fillable = [
        'user_id',
        'movie_id',
        'tv_id',
    ];

    public function movies()
    {
        return $this->belongsTo('App\Movie', 'movie_id')->withDefault();
    }
    public function tvseries()
    {
        return $this->belongsTo('App\TvSeries', 'tv_id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault();
    }
}
