<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'season_id',
        'added',
    ];

    public function users()
    {
        return $this->belongsTo('App\User')->withDefault();
    }

    public function tvseries()
    {
        return $this->belongsTo('App\TvSeries')->withDefault();
    }

    public function season()
    {
        return $this->belongsTo('App\Season')->withDefault();
    }
}
