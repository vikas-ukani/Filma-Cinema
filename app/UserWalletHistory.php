<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWalletHistory extends Model
{
    protected $fillable = [
        'wallet_id', 'type', 'log', 'amount', 'txn_id', 'expire_at',
    ];

    public function wallet()
    {
        return $this->belongsTo('App\UserWallet', 'wallet_id', 'id')->withDefault();
    }

}
