<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'interval'];

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

        foreach ($this->getTranslatableAttributes() as $interval) {
            $attributes[$interval] = $this->getTranslation($interval, app()->getLocale());
        }

        return $attributes;
    }

    protected $casts = [
        'feature' => 'array',
    ];

    protected $fillable = [
        'plan_id',
        'name',
        'currency',
        'currency_symbol',
        'amount',
        'interval',
        'interval_count',
        'trial_period_days',
        'screens',
        'download',
        'downloadlimit',
        'status',
        'free',
        'feature',
        'ads_in_app',
        'ads_in_web',
    ];

    public function pricing_texts()
    {
        return $this->hasOne('App\PricingText', 'package_id');
    }

}
