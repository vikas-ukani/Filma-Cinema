<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieSeries extends Model
{
    protected $fillable = [
        'movie_id',
        'series_movie_id',
    ];

    public function movie()
    {
        return $this->belongsTo('App\Movie', 'movie_id')->withDefault();
    }
}
