<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LikeController extends Controller
{
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $item = $request->item;
        $userid = Auth::user()->id;

        $like = like::where('user_id', $userid)->where('blog_id', $item)->first();
        if (isset($like)) {
            return response()->json('exist');
        } else {

            $input = $request->all();
            $input['added'] = 1;
            $input['blog_id'] = $item;
            $input['user_id'] = $userid;
            like::create($input);

            return response()->json('success');
        }

    }

    public function unlike(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $item = $request->item;
        $userid = Auth::user()->id;
        $unlike = like::where('user_id', $userid)->where('blog_id', $item)->get();
        if (isset($unlike) && count($unlike) > 0) {
            return "exist";
        } else {

            $input = $request->all();
            $input['added'] = -1;
            $input['blog_id'] = $item;
            $input['user_id'] = $userid;
            like::create($input);
        }

    }
}
