<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CustomPage extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'detail'];

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
        'in_show_menu',
        'detail',
        'slug',
        'is_active',

    ];

    public function menu()
    {
        return $this->belongsTo('App\Menu')->withDefault();
    }

}
