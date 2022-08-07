<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
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
        'name',
        'slug',
        'position',
    ];

    public function menu_data()
    {
        return $this->hasMany('App\MenuVideo', 'menu_id');
    }

    public function menusections()
    {
        return $this->hasMany('App\MenuSection', 'menu_id');
    }

    public function getblogs()
    {
        return $this->hasMany('App\BlogMenu', 'menu_id');
    }

    public function getpackage()
    {
        return $this->hasMany('App\PackageMenu', 'menu_id');
    }

    public function menugenreshow()
    {
        return $this->hasMany('App\MenuGenreShow', 'menu_id');
    }

    public function menu_sections()
    {
        return $this->hasMany('App\MenuSection', 'menu_id');
    }

}
