<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LandingPage extends Model
{
    use HasTranslations;

    public $translatable = ['heading', 'detail', 'button_text'];

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
        'image',
        'heading',
        'detail',
        'button',
        'button_text',
        'button_link',
        'left',
        'position',
    ];

}
