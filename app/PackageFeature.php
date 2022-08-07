<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PackageFeature extends Model
{
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
    protected $fillable = [
        'name',
    ];

    public function package()
    {
        return $this->hasMany('App\Package');
    }
}
