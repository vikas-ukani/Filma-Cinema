<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AudioLanguage extends Model
{
    use HasTranslations;

    public $translatable = ['language'];

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
        'language',
        'image',
        'status',
    ];

    public function movie()
    {
        return $this->hasMany('App\Movie', 'a_language');
    }

    public function seasons()
    {
        return $this->belongsTo('App\Season', 'a_language')->withDefault();
    }
}
