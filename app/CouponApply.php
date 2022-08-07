<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponApply extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'redeem',
    ];
}
