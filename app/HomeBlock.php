<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeBlock extends Model
{
    protected $fillable = [
        'movie_id',
        'tv_series_id',
        'is_active',
    ];

    public function movie()
    {
        return $this->belongsTo('App\Movie')->withDefault();
    }
    public function tvseries()
    {
        return $this->belongsTo('App\TvSeries', 'tv_series_id')->withDefault();
    }
}
