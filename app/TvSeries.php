<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TvSeries extends Model
{

    use HasTranslations;

    public $translatable = ['detail', 'keyword', 'description'];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    protected $casts = [
        'country' => 'array',
    ];

    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    protected $fillable = [
        'title',
        'keyword',
        'description',
        'tmdb',
        'tmdb_id',
        'thumbnail',
        'poster',
        'genre_id',
        'detail',
        'rating',
        'maturity_rating',
        'featured',
        'type',
        'fetch_by',
        'status',
        'created_by',
        'is_custom_label',
        'label_id',
        'is_upcoming',
        'upcoming_date',
        'is_kids',
        'country',
    ];

    protected $appends = [
        'user-rating',
    ];

    public function seasons()
    {
        return $this->hasMany('App\Season', 'tv_series_id');
    }

    public function seasons_first()
    {

        return $this->hasOne('App\Season', 'tv_series_id')->oldest();

    }

    public function episodes()
    {

        return $this->hasOne('App\Episode', 'seasons_id')->withDefault();

    }

    public function wishlist()
    {
        return $this->hasMany('App\Wishlist');
    }

    public function homeslide()
    {
        return $this->hasMany('App\HomeSlider', 'tv_series_id');
    }

    public function menus()
    {
        return $this->hasMany('App\MenuVideo');
    }
    public function comments()
    {

        return $this->hasMany('App\MovieComment', 'tv_series_id');
    }
    public function subcomments()
    {

        return $this->hasMany('App\MovieSubcomment', 'tv_series_id');
    }
    public function ratings()
    {
        return $this->hasMany('App\UserRating', 'tv_id');
    }
    public function getUserRatingAttribute()
    {
        return round($this->ratings()->avg('rating'), 2);
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by', 'id')->withDefault();
    }
    public function label()
    {
        return $this->belongsTo('App\Label')->withDefault();
    }
}
