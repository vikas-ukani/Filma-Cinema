<?php

namespace App\Http\Controllers;

use App\AppConfig;
use App\Director;
use App\Notifications\MyNotification;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;

 
class NotificationController extends Controller
{
  
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:notification.manage', ['only' => ['index', 'create', 'store']]);
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directors = Director::all();
        return view('admin.director.index', compact('directors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate([
            'title' => 'required',
        ]);
        if ($request->movie_id == "" && $request->tv_id == "" && $request->livetv == "") {

            $request->validate([
                'movie_id' => 'required',
                'tv_id' => 'required',
                'livetv' => 'required',
            ],
                [
                    'movie_id.required' => __('Please select at least one'),
                    'tv_id.required' => __('Please select at least one tv series'),
                    'livetv.required' => __('Please select at least one livetv'),
                ]);
            return back()->with('deleted', __('Notification has not been Sent. Please select atleast one movie,tvserie and live tv.'));
        } else {

            $user = User::all();
            $input = $request->all();

            $title = $request->title;
            $desc = $request->description;
            if ($request->movie_id != "") {
                $movie_id = $request->movie_id;
            } else {
                $movie_id = $request->livetv;
            }

            $tvid = $request->tv_id;

            $alluser[] = $input['user_id'];

            if (in_array("0", $input['user_id'])) {

                foreach ($user as $key => $value) {
                    $alluser[] = $value->id;
                    User::find($value->id)->notify(new MyNotification($title, $desc, $movie_id, $tvid, $alluser));

                    $appconfig = AppConfig::first();
                    if ($appconfig->push_key == 1) {
                        if (env('PUSH_AUTH_KEY') != null) {
                            Larafirebase::withTitle($title)
                                ->withBody($desc)

                                ->withClickAction('admin/notifications')
                                ->withPriority('high')
                                ->sendNotification(env('PUSH_AUTH_KEY'));
                        }
                    }

                }
                array_shift($alluser);
                $input['user_id'] = $alluser;

            } else {

                foreach ($input['user_id'] as $singleuser) {
                    User::find($singleuser)->notify(new MyNotification($title, $desc, $movie_id, $tvid, $alluser));

                    $appconfig = AppConfig::first();
                    if ($appconfig->push_key == 1) {
                        if (env('PUSH_AUTH_KEY') != null) {
                            Larafirebase::withTitle($title)
                                ->withBody($desc)
                                ->withClickAction('admin/notifications')
                                ->withPriority('high')
                                ->sendNotification(env('PUSH_AUTH_KEY'));
                        }
                    }
                }
                $input['user_id'] = $alluser;
            }

            return back()->with('added', __('Notification has been Sent'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function sendNotification()
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $user = User::first();

        $details = [
            'title' => 'title',
            'description' => 'description',

        ];

        Notification::send($user, new MyNotification($details));
        return back()->with('added', __('Notification is Sent'));

    }

    public function notificationread($id)
    {
        $userunreadnotification = auth()->
            user()->unreadNotifications->
            where('id', $id)->first();

        if ($userunreadnotification) {
            $userunreadnotification->markAsRead();
        }

        return 'Done';

    }

}
