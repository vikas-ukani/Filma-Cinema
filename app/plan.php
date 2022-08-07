<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class plan extends Model
{
    use Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image', 'email', 'password', 'plan', 'is_admin', 'stripe_id', 'card_brand', 'card_last_four', 'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
}
