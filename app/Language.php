<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'local',
        'name',
        'def',
        'rtl',
    ];

    public static function booted()
    {
        static::created(function ($lang) {

            if (!is_dir(resource_path() . "/lang/{$lang->local}")) {

                mkdir(resource_path() . "/lang/{$lang->local}");

                copy(resource_path() . "/lang/en/adminstaticwords.php", resource_path() . "/lang/{$lang->local}/adminstaticwords.php");
                copy(resource_path() . "/lang/en/staticwords.php", resource_path() . "/lang/{$lang->local}/staticwords.php");
              
            }

        });
    }
}
