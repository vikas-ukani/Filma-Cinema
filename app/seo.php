<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class seo extends Model
{

    use HasTranslations;

    public $translatable = ['Seo', 'author', 'metadata', 'keyword', 'description'];

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
        'fb',
        'google',
        'metadata',
        'keyword',
        'description',
        'author',
    ];
}
