<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Label extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    protected $fillable = ['name'];

    public function movies()
    {
        return $this->hasMany('App\Movie');
    }

    public function tvseries()
    {
        return $this->hasMany('App\TvSeries');
    }
}
