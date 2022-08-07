<?php

namespace App\Http\Controllers;

use App\Button;
use App\Config;
use Illuminate\Http\Request;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;


class ConfigController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('permission:site-settings.genral-settings', ['only' => ['index', 'update', 'verifiedupdate']]);
        $this->middleware('permission:site-settings.mail-settings', ['only' => ['getset', 'changeMailEnvKeys']]);
        $this->middleware('permission:site-settings.api-settings', ['only' => ['setApiView', 'changeEnvKeys']]);
    }

    public function getset()
    {
        $config = Config::first();

        $env_files = [
            'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
            'MAIL_DRIVER' => env('MAIL_DRIVER'),
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),

        ];
        return view('admin.mailsetting.mailset', compact('config', 'env_files'));

    }
    public function index()
    {
        $config = Config::first();
        $button = Button::first();

        $env_files = [
            'APP_NAME' => env('APP_NAME'),
            'APP_URL' => env('APP_URL'),
        ];

        return view('admin.config.settings', compact('config', 'button', 'env_files'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $active = @file_get_contents(public_path() . '/config.txt');
        $curdomain = @file_get_contents(public_path() . '/ddtl.txt');
        if (!$active) {
            $putS = 1;
            file_put_contents(public_path() . '/config.txt', $putS);
        }
        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);
        if ($domain == 'localhost' || strstr($domain, '.test') || strstr($domain, 'castleindia.in') || strstr($domain, '192.168.') || strstr($domain, 'mediacity.co.in')) {
            return $this->verifiedupdate($input, $request, $id);
        } else {

            $token = file_exists(storage_path() . '/app/keys/license.json') && file_get_contents(storage_path() . '/app/keys/license.json') != null ? file_get_contents(storage_path() . '/app/keys/license.json') : '';

            $token = json_decode($token);

            if ($token != '') {
                $token = $token->token;
            }

            $ch = curl_init();
            $options = array(
                CURLOPT_URL => "https://mediacity.co.in/purchase/public/api/check/$domain",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    "Authorization: Bearer " . $token,
                ),
            );

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);

            if (curl_errno($ch) > 0) {
                $message = __("Error connecting to API.");
                return back()->with('deleted', $message);
            }
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($responseCode == 200) {
                $body = json_decode($response);
                return $this->verifiedupdate($input, $request, $id);
            } else {
                $message = __("Failed");
                $putS = 0;
                @file_put_contents(public_path() . '/config.txt', $putS);
                return redirect('/');
            }
        }
    }

    public function verifiedupdate($input, $request, $id)
    {

        $config = Config::find($id);
        $button = Button::find($id);

        $input = $request->all();

        if (isset($request->APP_DEBUG)) {
            $env_update = DotenvEditor::setKeys(['APP_DEBUG' => 'true']);
        } else {
            $env_update = DotenvEditor::setKeys(['APP_DEBUG' => 'false']);
        }
        if (isset($request->COOKIE_CONSENT_ENABLED)) {
            $env_update = DotenvEditor::setKeys(['COOKIE_CONSENT_ENABLED' => 'true']);
        } else {
            $env_update = DotenvEditor::setKeys(['COOKIE_CONSENT_ENABLED' => 'false']);
        }

        $env_update = DotenvEditor::setKeys([
            'APP_NAME' => preg_replace('/\s+/', '', $request->title),
            'APP_URL' => preg_replace('/\s+/', '', $request->APP_URL),
        ]);

        if ($request->logo != null) {

            if (strstr($request->logo, '.png') || strstr($request->logo, '.jpg') || strstr($request->logo, '.jpeg') || strstr($request->logo, '.webp') || strstr($request->logo, '.gif')) {

                $input['logo'] = $request->logo;
                $config->update([
                    'logo' => $input['logo'],
                ]);

            } else {
                return back()->withInput()->with('deleted', __('Invalid file format Please use jpg,jpeg,png,webp and gif image format !'));
            }

        }

        if ($request->favicon != null) {

            if (strstr($request->favicon, '.png') || strstr($request->favicon, '.jpg') || strstr($request->favicon, '.jpeg') || strstr($request->favicon, '.webp') || strstr($request->favicon, '.gif')) {

                $input['favicon'] = $request->favicon;
                $config->update([
                    'favicon' => $input['favicon'],
                ]);

            } else {
                return back()->withInput()->with('deleted', __('Invalid file format Please use jpg,jpeg,png,webp and gif image format !'));
            }

        }

        if ($request->livetvicon != null) {

            if (strstr($request->livetvicon, '.png') || strstr($request->livetvicon, '.jpg') || strstr($request->livetvicon, '.jpeg') || strstr($request->livetvicon, '.webp') || strstr($request->livetvicon, '.gif')) {

                $input['livetvicon'] = $request->livetvicon;
                $config->update([
                    'livetvicon' => $input['livetvicon'],
                ]);

            } else {
                return back()->withInput()->with('deleted', __('Invalid file format Please use jpg,jpeg,png,webp and gif image format !'));
            }

        }

        if ($request->preloader_img != null) {

            if (strstr($request->preloader_img, '.png') || strstr($request->preloader_img, '.jpg') || strstr($request->preloader_img, '.jpeg') || strstr($request->preloader_img, '.webp') || strstr($request->preloader_img, '.gif')) {

                $input['preloader_img'] = $request->preloader_img;
                $config->update([
                    'preloader_img' => $input['preloader_img'],
                ]);

            } else {
                return back()->withInput()->with('deleted', __('Invalid file format Please use jpg,jpeg,png,webp and gif image format !'));
            }

        }

        if (!isset($input['age_restriction'])) {
            $input['age_restriction'] = 0;
        }

        if (!isset($input['prime_main_slider'])) {
            $input['prime_main_slider'] = 0;
        }

        if (!isset($input['prime_genre_slider'])) {
            $input['prime_genre_slider'] = 0;
        }

        if (!isset($input['donation'])) {
            $input['donation'] = 0;
        }

        if (!isset($input['prime_footer'])) {
            $input['prime_footer'] = 0;
        }

        if (!isset($input['prime_movie_single'])) {
            $input['prime_movie_single'] = 0;
        }

        if (!isset($input['stripe_payment'])) {
            $input['stripe_payment'] = 0;
        }
        if (!isset($input['instamojo_payment'])) {
            $input['instamojo_payment'] = 0;
        }

        if (!isset($input['paypal_payment'])) {
            $input['paypal_payment'] = 0;
        }

        if (!isset($input['omise_payment'])) {
            $input['omise_payment'] = 0;
        }

        if (!isset($input['payu_payment'])) {
            $input['payu_payment'] = 0;
        }

        if (!isset($input['paytm_payment'])) {

            $input['paytm_payment'] = 0;
        }

        if (!isset($input['paytm_test'])) {

            $input['paytm_test'] = 0;
        }

        if (!isset($input['preloader'])) {
            $input['preloader'] = 0;
        }

        if (!isset($input['catlog'])) {
            $input['catlog'] = 0;
        }

        if (!isset($input['withlogin'])) {
            $input['withlogin'] = 0;
        }

        if (!isset($input['inspect'])) {
            $input['inspect'] = 0;
        }

        if (!isset($input['rightclick'])) {
            $input['rightclick'] = 0;
        }

        if (!isset($input['goto'])) {
            $input['goto'] = 0;
        }

        if (isset($request->wel_eml)) {

            $input['wel_eml'] = 1;
        } else {
            $input['wel_eml'] = 0;
        }

        if (isset($request->blog)) {

            $input['blog'] = 1;
        } else {
            $input['blog'] = 0;
        }
        if (isset($request->free_sub)) {
            # code...
            $input['free_sub'] = 1;
        } else {
            $input['free_sub'] = 0;
        }

        if (isset($request->verify_email)) {

            $input['verify_email'] = 1;
        } else {
            $input['verify_email'] = 0;
        }
        if (isset($request->download)) {

            $input['download'] = 1;
        } else {
            $input['download'] = 0;
        }
        if (isset($request->is_playstore)) {

            $input['is_playstore'] = 1;
        } else {
            $input['is_playstore'] = 0;
        }
        if (isset($request->is_appstore)) {

            $input['is_appstore'] = 1;
        } else {
            $input['is_appstore'] = 0;
        }
        if (isset($request->color_dark)) {

            $input['color_dark'] = 1;
        } else {
            $input['color_dark'] = 0;
        }
        if (isset($request->user_rating)) {

            $input['user_rating'] = 1;
        } else {
            $input['user_rating'] = 0;
        }
        if (isset($request->comments)) {

            $input['comments'] = 1;
        } else {
            $input['comments'] = 0;
        }
        if (isset($request->comments_approval)) {

            $input['comments_approval'] = 1;
        } else {
            $input['comments_approval'] = 0;
        }
        if (isset($request->remove_landing_page)) {
            $input['remove_landing_page'] = 1;
        } else {
            $input['remove_landing_page'] = 0;
        }
        if (isset($request->coinpay)) {
            $input['coinpay'] = 1;
        } else {
            $input['coinpay'] = 0;
        }
        if (isset($request->captcha)) {
            $input['captcha'] = 1;
        } else {
            $input['captcha'] = 0;
        }
        if (isset($request->comming_soon)) {
            # code...
            $input['comming_soon'] = 1;
        } else {
            $input['comming_soon'] = 0;
        }
        if (isset($request->ip_block)) {
            # code...
            $input['ip_block'] = 1;
        } else {
            $input['ip_block'] = 0;
        }
        if (isset($request->maintenance)) {
            # code...
            $input['maintenance'] = 1;
        } else {
            $input['maintenance'] = 0;
        }
        if (isset($request->remove_subscription)) {
            # code...
            $input['remove_subscription'] = 1;
        } else {
            $input['remove_subscription'] = 0;
        }

        if (isset($request->protip)) {
            # code...
            $input['protip'] = 1;
        } else {
            $input['protip'] = 0;
        }

        if (isset($request->countviews)) {
            # code...
            $input['countviews'] = 1;
        } else {
            $input['countviews'] = 0;
        }

        if (isset($request->multiplescreen)) {
            # code...
            $input['multiplescreen'] = 1;
        } else {
            $input['multiplescreen'] = 0;
        }
        if (isset($request->aws)) {
            $input['aws'] = 1;
        } else {
            $input['aws'] = 0;
        }
        if (isset($request->two_factor)) {
            $input['two_factor'] = 1;
        } else {
            $input['two_factor'] = 0;
        }

        if (!isset($input['remove_ads'])) {
            $input['remove_ads'] = 0;
        } else {
            $input['remove_ads'] = 1;
        }

        if (!isset($input['remove_thumbnail'])) {
            $input['remove_thumbnail'] = 0;
        } else {
            $input['remove_thumbnail'] = 1;
        }

        if (!isset($input['is_toprated'])) {
            $input['is_toprated'] = 0;
        }

        if (!isset($input['reminder_mail'])) {
            $input['reminder_mail'] = 0;
        } else {
            $input['reminder_mail'] = 1;
        }

        if (!isset($input['kids_mode'])) {
            $input['kids_mode'] = 0;
        } else {
            $input['kids_mode'] = 1;
        }



        $config->update([
            'prime_main_slider' => $input['prime_main_slider'],
            'prime_genre_slider' => $input['prime_genre_slider'],
            'prime_footer' => $input['prime_footer'],
            'prime_movie_single' => $input['prime_movie_single'],
            'title' => $input['title'],
            'w_email' => $input['w_email'],
            'blog' => $input['blog'],
            'currency_code' => $input['currency_code'],
            'currency_symbol' => $input['currency_symbol'],
            'prime_footer' => $input['prime_footer'],
            'preloader' => $input['preloader'],
            'catlog' => $input['catlog'],
            'withlogin' => $input['withlogin'],
            'stripe_payment' => $input['stripe_payment'],
            'invoice_add' => $input['invoice_add'],
            'wel_eml' => $input['wel_eml'],
            'paytm_test' => $input['paytm_test'],
            'verify_email' => $input['verify_email'],
            'download' => $input['download'],
            'donation' => $input['donation'],
            'free_sub' => $input['free_sub'],
            'free_days' => $input['free_days'],
            'donation_link' => $input['donation_link'],
            'age_restriction' => $input['age_restriction'],
            'is_playstore' => $input['is_playstore'],
            'is_appstore' => $input['is_appstore'],
            'playstore' => $input['playstore'],
            'appstore' => $input['appstore'],
            'remove_landing_page' => $input['remove_landing_page'],
            'user_rating' => $input['user_rating'],
            'comments' => $input['comments'],
            'coinpay' => $input['coinpay'],
            'captcha' => $input['captcha'],
            'aws' => $input['aws'],
            'comments_approval' => $input['comments_approval'],

        ]);
        $button->update([
            'rightclick' => $input['rightclick'],
            'goto' => $input['goto'],
            'inspect' => $input['inspect'],
            'comming_soon' => $input['comming_soon'],
            'commingsoon_enabled_ip' => $request->commingsoon_enabled_ip,
            'comming_soon_text' => $request->comming_soon_text,
            'ip_block' => $input['ip_block'],
            'block_ips' => $request->block_ips,
            'remove_subscription' => $input['remove_subscription'],
            'protip' => $input['protip'],
            'multiplescreen' => $input['multiplescreen'],
            'two_factor' => $input['two_factor'],
            'countviews' => $input['countviews'],
            'remove_ads' => $input['remove_ads'],
            'is_toprated' => $input['is_toprated'],
            'toprated_count' => isset($input['toprated_count']) && $input['toprated_count'] != null ? $input['toprated_count'] : null,
            'remove_thumbnail' => $input['remove_thumbnail'],
            'reminder_mail' => $input['reminder_mail'],
            'kids_mode' => $input['kids_mode'],

        ]);

        $env_update->save();

        return back()->with('updated', __('Settings has been updated'));
    }

    public function setApiView()
    {
        $config = Config::first();
        $env_files = [
            'STRIPE_KEY' => env('STRIPE_KEY'),
            'STRIPE_SECRET' => env('STRIPE_SECRET'),
            'RAZOR_PAY_KEY' => env('RAZOR_PAY_KEY'),
            'RAZOR_PAY_SECRET' => env('RAZOR_PAY_SECRET'),
            'MAILCHIMP_APIKEY' => env('MAILCHIMP_APIKEY'),
            'MAILCHIMP_LIST_ID' => env('MAILCHIMP_LIST_ID'),
            'TMDB_API_KEY' => env('TMDB_API_KEY'),
            'PAYPAL_CLIENT_ID' => env('PAYPAL_CLIENT_ID'),
            'PAYPAL_SECRET_ID' => env('PAYPAL_SECRET_ID'),
            'PAYPAL_MODE' => env('PAYPAL_MODE'),
            'PAYU_METHOD' => env('PAYU_METHOD'),
            'PAYU_DEFAULT' => env('PAYU_DEFAULT'),
            'PAYU_MERCHANT_KEY' => env('PAYU_MERCHANT_KEY'),
            'PAYU_MERCHANT_SALT' => env('PAYU_MERCHANT_SALT'),
            'PAYTM_MID' => env('PAYTM_MID'),
            'PAYTM_MERCHANT_KEY' => env('PAYTM_MERCHANT_KEY'),
            'BTREE_ENVIRONMENT' => env('BTREE_ENVIRONMENT'),
            'BTREE_MERCHANT_ID' => env('BTREE_MERCHANT_ID'),
            'BTREE_PUBLIC_KEY' => env('BTREE_PUBLIC_KEY'),
            'BTREE_PRIVATE_KEY' => env('BTREE_PRIVATE_KEY'),
            'PAYSTACK_PUBLIC_KEY' => env('PAYSTACK_PUBLIC_KEY'),
            'PAYSTACK_SECRET_KEY' => env('PAYSTACK_SECRET_KEY'),
            'PAYSTACK_PAYMENT_URL' => env('PAYSTACK_PAYMENT_URL'),
            'MERCHANT_EMAIL' => env('MERCHANT_EMAIL'),
            'BTREE_MERCHANT_ACCOUNT_ID' => env('BTREE_MERCHANT_ACCOUNT_ID'),
            'COINPAYMENTS_MERCHANT_ID' => env('COINPAYMENTS_MERCHANT_ID'),
            'COINPAYMENTS_PUBLIC_KEY' => env('COINPAYMENTS_PUBLIC_KEY'),
            'COINPAYMENTS_PRIVATE_KEY' => env('COINPAYMENTS_PRIVATE_KEY'),
            'VIMEO_ACCESS' => env('VIMEO_ACCESS'),
            'YOUTUBE_API_KEY' => env('YOUTUBE_API_KEY'),
            'NOCAPTCHA_SITEKEY' => env('NOCAPTCHA_SITEKEY'),
            'NOCAPTCHA_SECRET' => env('NOCAPTCHA_SECRET'),
            'IM_API_KEY' => env('IM_API_KEY'),
            'IM_AUTH_TOKEN' => env('IM_AUTH_TOKEN'),
            'IM_URL' => env('IM_URL'),
            'MOLLIE_KEY' => env('MOLLIE_KEY'),
            'CASHFREE_APP_ID' => env('CASHFREE_APP_ID'),
            'CASHFREE_SECRET_ID' => env('CASHFREE_SECRET_ID'),
            'CASHFREE_API_END_URL' => env('CASHFREE_API_END_URL'),
            'key' => env('key'),
            'secret' => env('secret'),
            'region' => env('region'),
            'bucket' => env('bucket'),
            'OMISE_PUBLIC_KEY' => env('OMISE_PUBLIC_KEY') ? env('OMISE_PUBLIC_KEY') : '',
            'OMISE_SECRET_KEY' => env('OMISE_SECRET_KEY') ? env('OMISE_SECRET_KEY') : '',
            'OMISE_API_VERSION' => env('OMISE_API_VERSION') ? env('OMISE_API_VERSION') : '',
            'RAVE_PUBLIC_KEY' => env('RAVE_PUBLIC_KEY') ? env('RAVE_PUBLIC_KEY') : '',
            'RAVE_SECRET_KEY' => env('RAVE_SECRET_KEY') ? env('RAVE_SECRET_KEY') : '',
            'RAVE_LOGO' => env('RAVE_LOGO') ? env('RAVE_LOGO') : '',
            'RAVE_COUNTRY' => env('RAVE_COUNTRY') ? env('RAVE_COUNTRY') : '',
            'RAVE_PREFIX' => env('RAVE_PREFIX') ? env('RAVE_PREFIX') : 'rave',
            'RAVE_SECRET_HASH' => env('RAVE_SECRET_HASH') ? env('RAVE_SECRET_HASH') : '',
            'PAYHERE_BUISNESS_APP_CODE' => env('PAYHERE_BUISNESS_APP_CODE') ? env('PAYHERE_BUISNESS_APP_CODE') : '',
            'PAYHERE_APP_SECRET' => env('PAYHERE_APP_SECRET') ? env('PAYHERE_APP_SECRET') : '',
            'PAYHERE_MERCHANT_ID' => env('PAYHERE_MERCHANT_ID') ? env('PAYHERE_MERCHANT_ID') : '',
            'PAYHERE_MODE' => env('PAYHERE_MODE') ? env('PAYHERE_MODE') : '',

        ];
        return view('admin.config.api', compact('config', 'env_files'));
    }

    public function changeEnvKeys(Request $request)
    {

        $input = $request->all();
        // some code
        $env_update = DotenvEditor::setKeys([
            'STRIPE_KEY' => $request->STRIPE_KEY,
            'STRIPE_SECRET' => $request->STRIPE_SECRET,
            'RAZOR_PAY_KEY' => $request->RAZOR_PAY_KEY,
            'RAZOR_PAY_SECRET' => $request->RAZOR_PAY_SECRET,
            'MAILCHIMP_APIKEY' => $request->MAILCHIMP_APIKEY,
            'MAILCHIMP_LIST_ID' => $request->MAILCHIMP_LIST_ID,
            'TMDB_API_KEY' => $request->TMDB_API_KEY,
            'PAYPAL_CLIENT_ID' => $request->PAYPAL_CLIENT_ID,
            'PAYPAL_SECRET_ID' => $request->PAYPAL_SECRET_ID,
            'PAYPAL_MODE' => $request->PAYPAL_MODE,
            'PAYU_METHOD' => $request->PAYU_METHOD,
            'PAYU_DEFAULT' => $request->PAYU_DEFAULT,
            'PAYU_MERCHANT_KEY' => $request->PAYU_MERCHANT_KEY,
            'PAYU_MERCHANT_SALT' => $request->PAYU_MERCHANT_SALT,
            'PAYTM_MID' => $request->PAYTM_MID,
            'PAYTM_MERCHANT_KEY' => $request->PAYTM_MERCHANT_KEY,
            'YOUTUBE_API_KEY' => $request->YOUTUBE_API_KEY,
            'BTREE_ENVIRONMENT' => $request->BTREE_ENVIRONMENT,
            'BTREE_MERCHANT_ID' => $request->BTREE_MERCHANT_ID,
            'BTREE_PUBLIC_KEY' => $request->BTREE_PUBLIC_KEY,
            'BTREE_PRIVATE_KEY' => $request->BTREE_PRIVATE_KEY,
            'PAYSTACK_PUBLIC_KEY' => $request->PAYSTACK_PUBLIC_KEY,
            'PAYSTACK_SECRET_KEY' => $request->PAYSTACK_SECRET_KEY,
            'PAYSTACK_PAYMENT_URL' => $request->PAYSTACK_PAYMENT_URL,
            'MERCHANT_EMAIL' => $request->MERCHANT_EMAIL,
            'BTREE_MERCHANT_ACCOUNT_ID' => $request->BTREE_MERCHANT_ACCOUNT_ID,
            'COINPAYMENTS_MERCHANT_ID' => $request->COINPAYMENTS_MERCHANT_ID,
            'COINPAYMENTS_PUBLIC_KEY' => $request->COINPAYMENTS_PUBLIC_KEY,
            'COINPAYMENTS_PRIVATE_KEY' => $request->COINPAYMENTS_PRIVATE_KEY,
            'VIMEO_ACCESS' => $request->VIMEO_ACCESS,
            'NOCAPTCHA_SITEKEY' => $request->NOCAPTCHA_SITEKEY,
            'NOCAPTCHA_SECRET' => $request->NOCAPTCHA_SECRET,
            'IM_API_KEY' => $request->IM_API_KEY,
            'IM_AUTH_TOKEN' => $request->IM_AUTH_TOKEN,
            'IM_URL' => $request->IM_URL,
            'MOLLIE_KEY' => $request->MOLLIE_KEY,
            'CASHFREE_APP_ID' => $request->CASHFREE_APP_ID,
            'CASHFREE_SECRET_ID' => $request->CASHFREE_SECRET_ID,
            'CASHFREE_API_END_URL' => $request->CASHFREE_API_END_URL,
            'key' => $request->key,
            'secret' => $request->secret,
            'region' => $request->region,
            'bucket' => $request->bucket,
            'OMISE_PUBLIC_KEY' => $request->OMISE_PUBLIC_KEY,
            'OMISE_SECRET_KEY' => $request->OMISE_SECRET_KEY,
            'OMISE_API_VERSION' => $request->OMISE_API_VERSION,
            'RAVE_PUBLIC_KEY' => $request->RAVE_PUBLIC_KEY,
            'RAVE_SECRET_KEY' => $request->RAVE_SECRET_KEY,
            'RAVE_COUNTRY' => $request->RAVE_COUNTRY,
            'RAVE_LOGO' => $request->RAVE_LOGO,
            'RAVE_PREFIX' => $request->RAVE_PREFIX,
            'RAVE_SECRET_HASH' => $request->RAVE_SECRET_HASH,
            'PAYHERE_BUISNESS_APP_CODE' => $request->PAYHERE_BUISNESS_APP_CODE,
            'PAYHERE_APP_SECRET' => $request->PAYHERE_APP_SECRET,
            'PAYHERE_MERCHANT_ID' => $request->PAYHERE_MERCHANT_ID,
            'PAYHERE_MODE' => $request->PAYHERE_MODE == 1 ? 'live' : 'sandbox',

        ]);

        if (!isset($input['stripe_payment'])) {
            $input['stripe_payment'] = 0;
        }

        if (!isset($input['paypal_payment'])) {
            $input['paypal_payment'] = 0;
        }

        if (!isset($input['razorpay_payment'])) {
            $input['razorpay_payment'] = 0;
        }

        if ($request->instamojo_payment) {
            $input['instamojo_payment'] = 1;
        } else {
            $input['instamojo_payment'] = 0;
        }

        if ($request->omise_payment) {
            $input['omise_payment'] = 1;
        } else {
            $input['omise_payment'] = 0;
        }

        if ($request->mollie_payment) {
            $input['mollie_payment'] = 1;
        } else {
            $input['mollie_payment'] = 0;
        }

        if (!isset($input['cashfree_payment'])) {
            $input['cashfree_payment'] = 0;
        }

        if ($request->flutterrave_payment) {
            $input['flutterrave_payment'] = 1;
        } else {
            $input['flutterrave_payment'] = 0;
        }

        if ($request->payhere_payment) {
            $input['payhere_payment'] = 1;
        } else {
            $input['payhere_payment'] = 0;
        }

        if (!isset($input['payu_payment'])) {
            $input['payu_payment'] = 0;
        }

        if (!isset($input['paytm_payment'])) {
            $input['paytm_payment'] = 0;
        }
        if (!isset($input['bankdetails'])) {
            $input['bankdetails'] = 0;
        }
        if (!isset($input['braintree'])) {
            $input['braintree'] = 0;
        }
        if (!isset($input['paystack'])) {
            $input['paystack'] = 0;
        }
        if (!isset($input['coinpay'])) {
            $input['coinpay'] = 0;
        }

        if (isset($request->paytm_test)) {
            $input['paytm_test'] = 1;
        } else {
            $input['paytm_test'] = 0;
        }

        if (isset($request->captcha)) {
            $input['captcha'] = 1;
        } else {
            $input['captcha'] = 0;
        }

        $config = Config::first();

        $config->update($input);

        $env_update->save();

        if ($env_update) {
            return back()->with('updated', __('Api settings has been saved'));
        } else {
            return back()->with('deleted', __('Api settings could not be saved'));
        }

    }

    public function changeMailEnvKeys(Request $request)
    {
        $input = $request->all();
        // some code
        $env_update = DotenvEditor::setKeys([
            'MAIL_FROM_NAME' => $request->MAIL_FROM_NAME,
            'MAIL_DRIVER' => $request->MAIL_DRIVER,
            'MAIL_HOST' => $request->MAIL_HOST,
            'MAIL_PORT' => $request->MAIL_PORT,
            'MAIL_USERNAME' => $request->MAIL_USERNAME,
            'MAIL_FROM_ADDRESS' => $string = preg_replace('/\s+/', '', $request->MAIL_FROM_ADDRESS),
            'MAIL_PASSWORD' => $request->MAIL_PASSWORD,
            'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION,
        ]);

        $env_update->save();

        if ($env_update) {
            return back()->with('updated', __('Mail settings has been saved'));
        } else {
            return back()->with('deleted', __('Mail settings could not be saved'));
        }

    }

}
