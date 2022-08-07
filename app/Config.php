<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Config extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'invoice_add', 'terms_condition', 'privacy_pol', 'refund_pol', 'copyright'];

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

        return $attributes;
    }

    protected $fillable = [
        'logo',
        'w_name',
        'wel_eml',
        'title',
        'favicon',
        'livetvicon',
        'w_email',
        'verify_email',
        'stripe_pub_key',
        'stripe_secret_key',
        'paypal_mar_email',
        'currency_code',
        'currency_symbol',
        'invoice_add',
        'terms_condition',
        'privacy_pol',
        'refund_pol',
        'prime_main_slider',
        'prime_genre_slider',
        'donation',
        'donation_link',
        'prime_footer',
        'prime_movie_single',
        'copyright',
        'blog',
        'stripe_payment',
        'paypal_payment',
        'payu_payment',
        'paytm_payment',
        'paytm_test',
        'bankdetails',
        'download',
        'free_sub',
        'free_days',
        'account_no',
        'branch',
        'account_name',
        'ifsc_code',
        'bank_name',
        'preloader',
        'catlog',
        'withlogin',
        'inspect',
        'rightclick',
        'goto',
        'inspect',
        'color',
        'age_restriction',
        'is_playstore',
        'is_appstore',
        'playstore',
        'appstore',
        'color',
        'color_dark',
        'user_rating',
        'comments',
        'braintree',
        'paystack',
        'remove_landing_page', 'coinpay',
        'razorpay_payment',
        'captcha',
        'instamojo_payment',
        'mollie_payment',
        'cashfree_payment',
        'aws',
        'omise_payment',
        'flutterrave_payment',
        'comments_approval',
        'payhere_payment',
        'preloader_img',

    ];
}
