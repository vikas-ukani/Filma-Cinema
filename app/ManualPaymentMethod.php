<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualPaymentMethod extends Model
{
    protected $fillable = [
        'payment_name', 'description', 'thumbnail', 'status',
    ];
}
