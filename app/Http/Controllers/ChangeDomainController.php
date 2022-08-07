<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class ChangeDomainController extends Controller
{
    public function changedomain(Request $request)
    {

        $request->validate([
            'domain' => 'required',
        ]);

        $code = file_exists(storage_path() . '/app/keys/license.json') && file_get_contents(storage_path() . '/app/keys/license.json') != null ? file_get_contents(storage_path() . '/app/keys/license.json') : '';

        $code = json_decode($code);

        if ($code->code == '') {
            return back()->withInput()->withErrors(['domain' => __('Purchase code not found please contact support !')]);
        }

        $d = $request->domain;
        $domain = str_replace("www.", "", $d);
        $domain = str_replace("http://", "", $domain);
        $domain = str_replace("https://", "", $domain);

        $alldata = ['app_id' => "24626244", 'ip' => $request->ip(), 'domain' => $domain, 'code' => $code->code];

        $data = $this->make_request($alldata);

        if ($data['status'] == 1) {
            $put = 1;

            file_put_contents(public_path() . '/config.txt', $put);
            return redirect('/')->with('added', __('Domain permission changed successfully !'));
        } elseif ($data['msg'] == 'Already Register') {
            return back()->withInput()->withErrors(['domain' => __('User is already registered !')]);
        } else {
            return back()->withInput()->withErrors(['domain' => $data['msg']]);
        }

    }

    public function make_request($alldata)
    {
        $response = Http::post('https://mediacity.co.in/purchase/public/api/verifycode', [

            'app_id' => $alldata['app_id'],
            'ip' => $alldata['ip'],
            'code' => $alldata['code'],
            'domain' => $alldata['domain'],

        ]);

        $result = $response->json();

        if ($response->successful()) {

            if ($result['status'] == '1') {

                $lic_json = array(
                    'name' => config('app.name'),
                    'code' => $alldata['code'],
                    'type' => __('envato'),
                    'domain' => $alldata['domain'],
                    'lic_type' => __('regular'),
                    'token' => $result['token'],
                );

                $file = json_encode($lic_json);

                $filename = 'license.json';

                Storage::disk('local')->put('/keys/' . $filename, $file);

                return array(
                    'msg' => $result['message'],
                    'status' => '1',
                );

            } else {

                $message = $result['message'];

                return array(
                    'msg' => $message,
                    'status' => '0',
                );

            }

        } else {
            $message = __("Failed to validate");

            return array(
                'msg' => $message,
                'status' => '0',
            );
        }

    }
}
