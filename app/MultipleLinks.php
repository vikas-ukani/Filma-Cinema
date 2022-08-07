<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleLinks extends Model
{

    protected $fillable = [
        'download',
        'quality',
        'movie_id',
        'episode_id',
        'size',
        'language',
        'url', 'clicks',
    ];

    protected $casts = [
        'language' => 'array',
    ];

    public function movie()
    {
        return $this->belongsTo('App\Movie', 'movie_id', 'id')->withDefault();
    }
    public function episode()
    {
        return $this->belongsTo('App\Episode', 'episode_id', 'id')->withDefault();
    }
}
