<?php

namespace App\Providers;

use App\Affilate;
use App\AuthCustomize;
use App\Button;
use App\ChatSetting;
use App\Config;
use App\Language;
use App\seo;
use App\SocialIcon;
use App\WalletSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(121);
        Paginator::useBootstrapThree();

        try {

            DB::connection()->getDatabaseName();

            $data = array();
            if (Schema::hasTable('auth_customizes') && Schema::hasTable('configs') && Schema::hasTable('buttons') && Schema::hasTable('languages') && Schema::hasTable('users') && Schema::hasTable('chat_settings')) {

                $auth_customize = AuthCustomize::first();
                $lang = Language::all();
                $configs = Config::find(1);
                $com_name = $configs->w_name;
                $com_add = $configs->invoice_add;
                $catlog = $configs->catlog;
                $withlogin = $configs->withlogin;
                $com_email = $configs->w_email;
                $currency_code = $configs->currency_code;
                $currency_symbol = $configs->currency_symbol;
                $term_con = $configs->terms_condition;
                $pri_pol = $configs->privacy_pol;
                $refund_pol = $configs->refund_pol;
                $copyright = $configs->copyright;
                $logo = $configs->logo;
                $w_title = $configs->title;
                $w_email = $configs->email;
                $favicon = $configs->favicon;
                $prime_main_slider = $configs->prime_main_slider;
                $prime_genre_slider = $configs->prime_genre_slider;
                $prime_footer = $configs->prime_footer;
                $prime_movie_single = $configs->prime_movie_single;
                $stripe_payment = $configs->stripe_payment;
                $paypal_payment = $configs->paypal_payment;
                $paytm_payment = $configs->paytm_payment;
                $payu_payment = $configs->payu_payment;
                $braintree = $configs->braintree;
                $paystack = $configs->paystack;
                $coinpay = $configs->coinpay;
                $preloader = $configs->preloader;
                $button = Button::first();
                $inspect = $button->inspect;
                $rightclick = $button->rightclick;
                $goto = $button->goto;
                $color = $configs->color;
                $uc_browser = $button->uc_browser;
                $protip = $button->protip;
                $color_dark = $configs->color_dark;
                $remove_subscription = $button->remove_subscription;
                $mlt_screen = $button->multiplescreen;
                $whatsapp_settings = ChatSetting::where('key', 'whatsapp')->first();
                $messanger_settings = ChatSetting::where('key', 'messanger')->first();
                $currencies = DB::table('currencies')->get();
                $af_system = Affilate::first();
                $walletsetting = WalletSettings::first();
                $si = SocialIcon::first();

                $seo = seo::find(1);
                $fb = $seo->fb;
                $google = $seo->google;
                $description = $seo->description;
                $keyword = $seo->keyword;
                $author = $seo->author;

                $omdbApiKey = env('OMDB_API_KEY');
                $tmdbApiKey = env('TMDB_API_KEY');

                $data = array(
                    'paytm_payment' => $paytm_payment ?? '',
                    'author' => $author ?? '',
                    'color' => $color ?? '',
                    'color_dark' => $color_dark ?? '',
                    'description' => $description ?? '',
                    'keyword' => $keyword ?? '',
                    'goto' => $goto ?? '',
                    'fb' => $fb ?? '',
                    'google' => $google ?? '',
                    'rightclick' => $rightclick ?? '',
                    'inspect' => $inspect ?? '',
                    'uc_browser' => $uc_browser ?? '',
                    'company_name' => $com_name ?? '',
                    'w_email' => $com_email ?? '',
                    'invoice_add' => $com_add ?? '',
                    'auth' => $auth ?? '',
                    'prime_main_slider' => $prime_main_slider ?? '',
                    'prime_genre_slider' => $prime_genre_slider ?? '',
                    'prime_footer' => $prime_footer ?? '',
                    'prime_movie_single' => $prime_movie_single ?? '',
                    'omdbapikey' => $omdbApiKey ?? '',
                    'tmdbapikey' => $tmdbApiKey ?? '',
                    'currency_code' => $currency_code ?? '',
                    'currency_symbol' => $currency_symbol ?? '',
                    'logo' => $logo ?? '',
                    'favicon' => $favicon ?? '',
                    'term_con' => $term_con ?? '',
                    'pri_pol' => $pri_pol ?? '',
                    'refund_pol' => $refund_pol ?? '',
                    'copyright' => $copyright ?? '',
                    'w_title' => $w_title ?? '',
                    'lang' => $lang ?? '',
                    'braintree' => $braintree ?? '',
                    'paystack' => $paystack ?? '',
                    'coinpay' => $coinpay ?? '',
                    'stripe_payment' => $stripe_payment ?? '',
                    'paypal_payment' => $paypal_payment ?? '',
                    'payu_payment' => $payu_payment ?? '',
                    'auth_customize' => $auth_customize ?? '',
                    'preloader' => $preloader ?? '',
                    'protip' => $protip ?? '',
                    'remove_subscription' => $remove_subscription ?? '',
                    'configs' => $configs ?? '',
                    'mlt_screen' => $mlt_screen ?? '',
                    'button' => $button ?? '',
                    'whatsapp_settings' => $whatsapp_settings ?? '',
                    'messanger_settings' => $messanger_settings ?? '',
                    'currencies' => $currencies ?? '',
                    'af_system' => $af_system ?? '',
                    'walletsetting' => $walletsetting ?? '',
                    'si' => $si
                );

                view()->composer('*', function ($view) use ($data) {

                    try {
                        $view->with([
                            'paytm_payment' => $data['paytm_payment'],
                            'author' => $data['author'],
                            'color' => $data['color'],
                            'color_dark' => $data['color_dark'],
                            'description' => $data['description'],
                            'keyword' => $data['keyword'],
                            'goto' => $data['goto'],
                            'fb' => $data['fb'],
                            'google' => $data['google'],
                            'rightclick' => $data['rightclick'],
                            'inspect' => $data['inspect'],
                            'uc_browser' => $data['uc_browser'],
                            'company_name' => $data['company_name'],
                            'w_email' => $data['w_email'],
                            'invoice_add' => $data['invoice_add'],
                            'auth' => auth()->user(),
                            'prime_main_slider' => $data['prime_main_slider'],
                            'prime_genre_slider' => $data['prime_genre_slider'],
                            'prime_footer' => $data['prime_footer'],
                            'prime_movie_single' => $data['prime_movie_single'],
                            'omdbapikey' => $data['omdbapikey'],
                            'tmdbapikey' => $data['tmdbapikey'],
                            'currency_code' => $data['currency_code'],
                            'currency_symbol' => $data['currency_symbol'],
                            'logo' => $data['logo'],
                            'favicon' => $data['favicon'],
                            'term_con' => $data['term_con'],
                            'pri_pol' => $data['pri_pol'],
                            'refund_pol' => $data['refund_pol'],
                            'copyright' => $data['copyright'],
                            'w_title' => $data['w_title'],
                            'lang' => $data['lang'],
                            'braintree' => $data['braintree'],
                            'paystack' => $data['paystack'],
                            'coinpay' => $data['coinpay'],
                            'stripe_payment' => $data['stripe_payment'],
                            'paypal_payment' => $data['paypal_payment'],
                            'payu_payment' => $data['payu_payment'],
                            'auth_customize' => $data['auth_customize'],
                            'preloader' => $data['preloader'],
                            'protip' => $data['protip'],
                            'remove_subscription' => $data['remove_subscription'],
                            'configs' => $data['configs'],
                            'mlt_screen' => $data['mlt_screen'],
                            'button' => $data['button'],
                            'whatsapp_settings' => $data['whatsapp_settings'],
                            'messanger_settings' => $data['messanger_settings'],
                            'currencies' => $data['currencies'],
                            'af_system' => $data['af_system'],
                            'walletsetting' => $data['walletsetting'],
                            'si' => $data['si']
                        ]);
                    } catch (\Exception $e) {

                    }
                });

            }

        } catch (\Exception $e) {

        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();
    }
}
