<?php

namespace App\Http\Controllers;

use App\Notifications\OfferPushNotifications;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

 
class PushNotificationsController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('permission:pushnotification.settings', ['only' => ['index', 'push', 'updatekeys']]);
    }

    public function index()
    {
        return view('admin.pushnotifications.index');
    }

    public function push(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        ini_set('max_excecution_time', -1);

        ini_set('memory_limit', -1);

        $request->validate([
            'subject' => 'required|string',
            'message' => 'required',
        ]);

        if (env('ONESIGNAL_APP_ID') == '' && env('ONESIGNAL_REST_API_KEY') == '') {

            return back()->withInput()->with('deleted',__('Please update onesignal keys in settings !, Keys not found !'));
        }

        try {

            $usergroup = User::query();

            $data = [
                'subject' => $request->subject,
                'body' => $request->message,
                'target_url' => $request->target_url ?? null,
                'icon' => $request->icon ?? null,
                'image' => $request->image ?? null,
                'buttonChecked' => $request->show_button ? "yes" : "no",
                'button_text' => $request->btn_text ?? null,
                'button_url' => $request->btn_url ?? null,
            ];

            if ($request->user_group == 'all_customers') {

                $users = $usergroup->select('id')->where('is_admin', '!=', 1)->get();

            } elseif ($request->user_group == 'all_admins') {

                $users = $usergroup->select('id')->where('is_admin', '=', 1)->get();

            } else {
                // all users
                $users = $usergroup->select('id')->get();
            }

            $users = $usergroup->select('id')->get();

            Notification::send($users, new OfferPushNotifications($data));

            return back()->with('added', __('Notification pushed successfully !'));

        } catch (\Exception $e) {

            return back()->withInput()->with('deleted', $e->getMessage());

        }

    }

    public function updateKeys(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate([
            'ONESIGNAL_APP_ID' => 'required|string',
            'ONESIGNAL_REST_API_KEY' => 'required|string',
        ], [
            'ONESIGNAL_APP_ID.required' => __('OneSignal app id is required'),
            'ONESIGNAL_REST_API_KEY.required' => __('Onesignal rest api key is required'),
        ]);

        $env_keys_save = DotenvEditor::setKeys([
            'ONESIGNAL_APP_ID' => $request->ONESIGNAL_APP_ID,
            'ONESIGNAL_REST_API_KEY' => $request->ONESIGNAL_REST_API_KEY,
        ]);

        $env_keys_save->save();

        return back()->with('added', __('OneSignal Keys updated successfully !'));
    }
}
