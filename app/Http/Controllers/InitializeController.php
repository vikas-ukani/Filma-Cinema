<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class InitializeController extends Controller
{
   
    public function verify(Request $request)
    {

        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);

        $alldata = ['app_id' => "24626244", 'ip' => $request->ip(), 'domain' => $domain, 'code' => $request->code];

        $data = $this->make_request($alldata);

        if ($data['status'] == 1) {
            $put = 1;
            file_put_contents(public_path() . '/config.txt', $put);

            $status = 'complete';
            $status = Crypt::encrypt($status);
            @file_put_contents(public_path() . '/step3.txt', $status);

            $draft = 'gotostep1';
            $draft = Crypt::encrypt($draft);
            @file_put_contents(public_path() . '/draft.txt', $draft);

            return redirect()->route('db.setup');
        } elseif ($data['msg'] == 'Already Register') {
            return redirect()->route('verifylicense')->withErrors(['User is already registered']);
        } else {
            notify()->error($data['msg']);
            return back()->withErrors([$data['msg']]);
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

                    'name' => request()->user_id,
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
            $message = "Failed to validate";

            return array(
                'msg' => $message,
                'status' => '0',
            );
        }

    }

}
