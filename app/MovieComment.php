<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieComment extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'comment',
        'movie_id',
        'tv_series_id',
        'status',

    ];

    public function movies()
    {
        return $this->belongsTo('App\Movie')->withDefault();
    }
    public function tvseries()
    {
        return $this->belongsTo('App\TvSeries')->withDefault();
    }
    public function subcomments()
    {
        return $this->hasmany('App\MovieSubcomment', 'comment_id');
    }
    public function users()
    {
        return $this->hasmany('App\User');
    }
}
