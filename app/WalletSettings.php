<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletSettings extends Model
{

    protected $fillable = ['enable_wallet', 'paytm_enable', 'paypal_enable', 'stripe_enable'];

}
