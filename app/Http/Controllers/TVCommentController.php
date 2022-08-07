<?php

namespace App\Http\Controllers;

use App\Config;
use App\MovieComment;
use App\MovieSubcomment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TVCommentController extends Controller
{

    public function store(Request $request, $id)
    {
        $config = Config::first();
        if ($config->comments == 1 && $config->comments_approval == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        if (!is_null($request->email)) {
            $email = $request->email;
        } else {
            $email = Auth::user()->email;
        }

        $input = $request->all();
        $input['tv_series_id'] = $id;
        $input['user_id'] = Auth::user()->id;
        $input['name'] = Auth::user()->name;
        $input['email'] = $email;
        $input['status'] = $status;
        $data = MovieComment::create($input);

        return back()->with('added', __('Your Comment has been added'));

    }

    public function reply(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $input = $request->all();
        $input['comment_id'] = $id;
        $input['user_id'] = $user_id;
        $data = MovieSubcomment::create($input);
        return back()->with('added', __('Your reply has been added'));
    }

    public function deletecomment($id)
    {

        $comment_delete = MovieComment::findOrFail($id);
        if (isset($comment_delete->subcomment)) {
            foreach ($comment_delete->subcomment as $sub) {
                $sub->delete();
            }
        }

        $comment_delete->delete();
        return back()->with('deleted', __('Comment has been deleted'));
    }

    public function deletesubcomment($cid)
    {
        $subcomment = MovieSubcomment::findOrFail($cid);
        $subcomment->delete();
        return back()->with('deleted',__('SubComment has been deleted'));
    }

}
