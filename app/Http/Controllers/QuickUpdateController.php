<?php

namespace App\Http\Controllers;

use App\Movie;
use App\MovieComment;
use App\MovieSubcomment;
use App\TvSeries;


class QuickUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:movies.edit', ['only' => ['change']]);
        $this->middleware('permission:tvseries.edit', ['only' => ['changetvstatus']]);
        $this->middleware('permission:comment-settings.comments', ['only' => ['commentchange']]);
        $this->middleware('permission:comment-settings.subcomments', ['only' => ['subcomentchange']]);
    }
    public function change($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $movie = Movie::findorfail($id);

        if ($movie->status == 1) {
            $movie->status = 0;
        } else {
            $movie->status = 1;
        }

        $movie->save();
        return back()->with('updated', __('Movie Status changed !'));
    }

    public function changetvstatus($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $tv = TvSeries::findorfail($id);

        if ($tv->status == 1) {
            $tv->status = 0;
        } else {
            $tv->status = 1;
        }

        $tv->save();
        return back()->with('updated', __('TvSeries Status changed !'));
    }

    public function commentchange($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $comment = MovieComment::findorfail($id);

        if ($comment->status == 1) {
            $comment->status = 0;
        } else {
            $comment->status = 1;
        }

        $comment->save();
        return back()->with('updated', __('Comment Status changed !'));
    }

    public function subcommentchange($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $comment = MovieSubcomment::findorfail($id);

        if ($comment->status == 1) {
            $comment->status = 0;
        } else {
            $comment->status = 1;
        }

        $comment->save();
        return back()->with('updated', __('SubComment Status changed !'));
    }

}
