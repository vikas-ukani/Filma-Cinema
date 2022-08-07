<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{

    protected $fillable = ['balance', 'status', 'user_id'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function wallethistory()
    {
        return $this->hasMany('App\UserWalletHistory', 'wallet_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo('App\User', 'id', 'user_id')->withDefault();
    }
}
