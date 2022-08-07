<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualPayment extends Model
{

    protected $fillable = [
        'user_id',
        'payment_id',
        'user_name',
        'package_id',
        'price',
        'status',
        'file',
        'method',
        'subscription_from',
        'subscription_to',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->withDefault();
    }

    public function plan()
    {
        return $this->belongsTo('App\Package', 'package_id')->withDefault();
    }
}
