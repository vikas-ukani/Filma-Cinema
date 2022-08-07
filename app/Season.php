<?php

namespace App;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Season extends Model implements Viewable
{
    use HasTranslations;
    use InteractsWithViews;

    public $translatable = ['detail'];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    protected $fillable = [
        'tv_series_id',
        'season_no',
        'publish_year',
        'a_language',
        'subtitle',
        'subtitle_list',
        'type',
        'thumbnail',
        'poster',
        'tmdb_id',
        'tmdb',
        'detail',
        'actor_id',
        'is_protect',
        'season_slug',
        'password',
        'trailer_url',
    ];

    public function episodes()
    {
        return $this->hasMany('App\Episode', 'seasons_id');
    }

    public function firstEpisode()
    {
        return $this->hasOne('App\Episode', 'seasons_id')->oldest();
    }

    public function tvseries()
    {
        return $this->belongsTo('App\TvSeries', 'tv_series_id')->withDefault();
    }

    public function wishlist()
    {
        return $this->hasMany('App\Wishlist');
    }
}
