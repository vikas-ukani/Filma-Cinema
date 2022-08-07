<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PricingText extends Model
{

    use HasTranslations;

    public $translatable = ['title1', 'title2', 'title3', 'title4', 'title5', 'title6'];

    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    protected $fillable = [
        'title1', 'title2', 'title3', 'title4', 'title5', 'title6',
        'package_id',
    ];

}
