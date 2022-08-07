<?php

namespace App\Http\Controllers;

use App;
use App\AppConfig;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;


class AppConfigController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:app-settings.setting', ['only' => ['index', 'update', 'createKey', 'keysupdate']]);
    }

    public function index()
    {
        $appconfig = AppConfig::first();
        return view('admin.appconfig.appsettings', compact('appconfig'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppConfig  $appConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $appconfig = AppConfig::findOrFail($id);
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $input = $request->all();

        // logo
        if ($file = $request->file('logo')) {
            $name = 'applogo_' . time() . $file->getClientOriginalName();
            if ($appconfig->logo != null) {
                $content = @file_get_contents(public_path() . '/images/app/logo/' . $appconfig->logo);
                if ($content) {
                    unlink(public_path() . '/images/app/logo/' . $appconfig->logo);
                }
            }
            $file->move('images/app/logo', $name);
            $input['logo'] = $name;
            $appconfig->update([
                'logo' => $input['logo'],
            ]);
        }

        //payment
        if (isset($input['stripe_payment'])) {
            $input['stripe_payment'] = 1;
        } else {
            $input['stripe_payment'] = 0;
        }

        if (isset($input['paypal_payment'])) {
            $input['paypal_payment'] = 1;
        } else {
            $input['paypal_payment'] = 0;
        }

        if (isset($input['razorpay_payment'])) {
            $input['razorpay_payment'] = 1;
        } else {
            $input['razorpay_payment'] = 0;
        }

        if (isset($input['brainetree_payment'])) {
            $input['brainetree_payment'] = 1;
        } else {
            $input['brainetree_payment'] = 0;
        }

        if (isset($input['paystack_payment'])) {
            $input['paystack_payment'] = 1;
        } else {
            $input['paystack_payment'] = 0;
        }
        if (isset($input['paytm_payment'])) {
            $input['paytm_payment'] = 1;
        } else {
            $input['paytm_payment'] = 0;
        }

        if (!isset($input['bankdetails'])) {
            $input['bankdetails'] = 0;
        }

        if (isset($input['instamojo_payment'])) {
            $input['instamojo_payment'] = 1;
        } else {
            $input['instamojo_payment'] = 0;
        }

        // social login
        if (!isset($input['fb_check'])) {
            $input['fb_check'] = 0;
        }
        if (!isset($input['google_login'])) {
            $input['google_login'] = 0;
        }
        if (!isset($input['amazon_login'])) {
            $input['amazon_login'] = 0;
        }
        if (!isset($input['git_lab_check'])) {
            $input['git_lab_check'] = 0;
        }
        if (!isset($input['inapp_payment'])) {
            $input['inapp_payment'] = 0;
        }
        if (!isset($input['push_key'])) {
            $input['push_key'] = 0;
        }
        if (!isset($input['banner_admob'])) {
            $input['banner_admob'] = 0;
        }
        if (!isset($input['interstitial_admob'])) {
            $input['interstitial_admob'] = 0;
        }

        if (!isset($input['remove_ads'])) {
            $input['remove_ads'] = 0;
        } else {
            $input['remove_ads'] = 1;
        }

        if (!isset($input['is_admob'])) {
            $input['is_admob'] = 0;
        } else {
            $input['is_admob'] = 1;
        }

        $env_update = DotenvEditor::setKeys(['PUSH_AUTH_KEY' => $request->PUSH_AUTH_KEY]);
        $env_update->save();

        $appconfig->update([
            'title' => $input['title'],
            'bankdetails' => $input['bankdetails'],
            'stripe_payment' => $input['stripe_payment'],
            'paypal_payment' => $input['paypal_payment'],
            'razorpay_payment' => $input['razorpay_payment'],
            'brainetree_payment' => $input['brainetree_payment'],
            'paystack_payment' => $input['paystack_payment'],
            'fb_check' => $input['fb_check'],
            'google_login' => $input['google_login'],
            'banner_admob' => $input['banner_admob'],
            'interstitial_admob' => $input['interstitial_admob'],
            'banner_id' => $input['banner_id'],
            'interstitial_id' => $input['interstitial_id'],
            'amazon_login' => $input['amazon_login'],
            'git_lab_check' => $input['git_lab_check'],
            'inapp_payment' => $input['inapp_payment'],
            'push_key' => $input['push_key'],
            'remove_ads' => $input['remove_ads'],
            'paytm_payment' => $input['paytm_payment'],
            'is_admob' => $input['is_admob'],
            'instamojo_payment' => $input['instamojo_payment'],

        ]);

        return back()->with('updated', __('App Settings has been updated'));

    }

    public function createKey(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);
        if (strstr($domain, 'localhost') || strstr($domain, '192.168.') || strstr($domain, '.test') || strstr($domain, 'mediacity.co.in') || strstr($domain, 'castleindia.in')) {
            $put = 1;
            file_put_contents(public_path() . '/config.txt', $put);

            return $this->keysupdate($request);
        } else {

            $request->validate([
                'purchase_code' => 'required',
            ],
                [
                    'purchase_code.required' => __('Please enter your envato purchase code !'),
                ]);

            $code = request()->purchase_code;

            $personalToken = "inNy83FTjV2CTPqvNdPGRr2mAJ0raPC4";
            if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $code)) {
                //throw new Exception("Invalid code");
                $message = __("Invalid Purchase Code");
                return back()->withErrors($message)->withInput();
            }
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$personalToken}",
                ),
            ));
            // Send the request with warnings supressed
            $response = curl_exec($ch);
            // Handle connection errors (such as an API outage)
            if (curl_errno($ch) > 0) {
                //throw new Exception("Error connecting to API: " . curl_error($ch));
                $message = __("Error connecting to API !");
                return back()->withErrors($message)->withInput();
            }
            // If we reach this point in the code, we have a proper response!
            // Let's get the response code to check if the purchase code was found
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // HTTP 404 indicates that the purchase code doesn't exist
            if ($responseCode === 404) {
                //throw new Exception("The purchase code was invalid");
                $message = __("Purchase Code is invalid");
                return back()->withErrors($message)->withInput();
            }
            // Anything other than HTTP 200 indicates a request or API error
            // In this case, you should again ask the user to try again later
            if ($responseCode !== 200) {
                //throw new Exception("Failed to validate code due to an error: HTTP {$responseCode}");

                $message = __("Failed to validate code.");
                return back()->withErrors($message)->withInput();
            }
            // Parse the response into an object with warnings supressed
            $body = json_decode($response);
            // Check for errors while decoding the response (PHP 5.3+)
            if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
                //new Exception("Error parsing response");
                $message = __("Can't Verify Now.");
                return back()->withErrors($message)->withInput();
            }
            if ($body->item->id == '24626244') {

                return $this->keysupdate($request);

                return back()->withInput()->with('added', __('Keys Updated successfully'));
            } else {

                $message = __("Please enter Nexthour App purchase code.");

                return back()->withInput()->with('added', $message);
            }

        }

    }

    public function keysupdate(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $row = AppConfig::first();
        if ($row) {
            $row->update(['generate_apikey' => (string) Str::uuid()]);

            return back()->with('added', __('Key is re-generated successfully !'));

        } else {

            $row->update([
                'generate_apikey' => (string) Str::uuid(),
            ]);
            if ($row) {

                return back()->with('added', __('API Key has been generated successfully !'));
            }
        }

    }

}
