<?php

namespace App;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model implements Viewable
{
    use HasTranslations;

    use InteractsWithViews;

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
        'fetch_by',
        'keyword',
        'description',
        'tmdb_id',
        'duration',
        'thumbnail',
        'poster',
        'tmdb',
        'director_id',
        'actor_id',
        'supporting_actor',
        'genre_id',
        'trailer_url',
        'detail',
        'views',
        'rating',
        'upload_video',
        'maturity_rating',
        'subtitle',
        'publish_year',
        'released',
        'featured',
        'series',
        'a_language',
        'audio_files',
        'type',
        'live',
        'livetvicon',
        'status',
        'is_protect',
        'password',
        'slug',
        'created_by',
        'is_upcoming',
        'upcoming_date',
        'is_custom_label',
        'label_id',
        'is_kids',
        'country',
    ];

    protected $appends = [
        'user-rating',
    ];

    public function genre()
    {
        return $this->belongsTo('App\Genre')->withDefault();
    }

    public function movie_series()
    {
        return $this->hasMany('App\MovieSeries', 'movie_id');
    }

    public function wishlist()
    {
        return $this->hasMany('App\Wishlist');
    }

    public function video_link()
    {
        return $this->hasOne('App\Videolink');
    }

    public function menus()
    {
        return $this->hasMany('App\MenuVideo');
    }
    public function subtitles()
    {
        return $this->hasMany('App\Subtitles', 'm_t_id');
    }
    public function comments()
    {

        return $this->hasMany('App\MovieComment', 'movie_id');
    }
    public function subcomments()
    {

        return $this->hasMany('App\MovieSubcomment', 'movie_id');
    }
    public function ratings()
    {
        return $this->hasMany('App\UserRating', 'movie_id');
    }
    public function getUserRatingAttribute()
    {
        return round($this->ratings()->avg('rating'), 2);
    }
    public function multilinks()
    {
        return $this->hasMany('App\MultipleLinks', 'movie_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by', 'id')->withDefault();
    }
    public function label()
    {
        return $this->belongsTo('App\Label', 'label_id', 'id')->withDefault();
    }

}
