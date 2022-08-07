<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Subcomment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $request->validate([
            'name' => 'required',

            'comment' => 'required',

        ]);
        if (!is_null($request->email)) {
            $email = $request->email;
        } else {
            $email = Auth::user()->email;
        }

        $input = $request->all();
        $input['blog_id'] = $id;
        $input['email'] = $email;
        $input['user_id'] = Auth::user()->id;
        Comment::create($input);

        return back()->with('added', __('Your Comment has been added'));

    }

    public function reply(Request $request, $id, $bid)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $request->validate([

            'reply' => 'required',

        ]);
        $user_id = Auth::user()->id;
        $input = $request->all();
        $input['comment_id'] = $id;
        $input['blog_id'] = $bid;
        $input['user_id'] = $user_id;
        Subcomment::create($input);
        return back()->with('added', __('Your reply has been added'));
    }

    public function deletecomment($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $comment_delete = Comment::findOrFail($id);
        if (isset($comment_delete->subcomments)) {
            foreach ($comment_delete->subcomments as $sub) {
                $sub->delete();
            }
        }

        $comment_delete->delete();
        return back()->with('deleted', __('Comment has been deleted'));
    }

    public function deletesubcomment($bid)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $subcomment = Subcomment::findOrFail($bid);
        $subcomment->delete();
        return back()->with('deleted',__('SubComment has been deleted'));
    }

}
