<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use SamuelNitsche\AuthLog\AuthLogable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable, Billable, HasApiTokens, HasRoles, AuthLogable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image', 'email', 'password', 'is_admin', 'stripe_id', 'card_brand', 'card_last_four', 'trial_ends_at', 'google_id', 'facebook_id', 'gitlab_id', 'verifyToken', 'dob', 'age', 'is_blocked', 'code', 'dob', 'mobile', 'status',
        'braintree_id', 'is_assistant', 'amazon_id', 'google2fa_secret', 'google2fa_enable', 'refer_code', 'refered_from','facebook_url','youtube_url','twitter_url','address','country','state','city',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function countries()
    {
        return $this->belongsTo('App\Country', 'country')->withDefault();
    }

    public function state()
    {
        return $this->belongsTo('App\State','state')->withDefault();
    }

    public function city()
    {
        return $this->belongsTo('App\City','city')->withDefault();
    }

    public function wishlist()
    {
        return $this->hasMany('App\Wishlist');
    }

    public function paypal_subscriptions()
    {
        return $this->hasMany('App\PaypalSubscription');
    }

    public function subscriptions()
    {
        return $this->hasMany('Laravel\Cashier\Subscription');
    }

    public function watch_history()
    {
        return $this->hasMany('App\WatchHistory');
    }

    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => [$this->id . ""]];
    }

    public function getReferals()
    {
        return $this->hasMany('App\AffilateHistory', 'user_id');
    }

    public function onetimereferdata()
    {
        return $this->hasOne('App\AffilateHistory', 'refer_user_id', 'id');
    }

    public static function createReferCode()
    {

        $aff_settings = Affilate::first();

        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');

        shuffle($seed);

        $rand = '';
        foreach (array_rand($seed, $aff_settings->code_limit) as $k) {
            $rand .= $seed[$k];
        }

        $num = str_split('');
        shuffle($num);

        return Str::upper($rand);
    }

    public function wallet()
    {
        return $this->hasOne('App\UserWallet', 'user_id', 'id');
    }

    public function userRating()
    {
        return $this->hasMany('App\UserRating', 'user_id', 'id');
    }

}
