<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Director extends Model
{

    use HasTranslations;

    public $translatable = ['name', 'biography'];
    

    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    protected $fillable = [
        'name', 'image', 'biography', 'place_of_birth', 'DOB',
    ];
}
