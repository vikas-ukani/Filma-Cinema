<?php

namespace App\Http\Controllers;

use App\Movie;
use App\MovieComment;
use App\MovieSubcomment;
use App\TvSeries;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:comment-settings.comments', ['only' => ['index', 'destroy', 'bulk_delete']]);
        $this->middleware('permission:comment-settings.subcomments', ['only' => ['subcommentindex', 'subcommentdestroy', 'sub_bulk_delete']]);
    }
    public function index(Request $request)
    {
        $comment = MovieComment::get();
        if ($request->ajax()) {
            return \Datatables::of($comment)

                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $html = '<div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="' . $row->id . '" id="checkbox' . $row->id . '">
                    <label for="checkbox' . $row->id . '" class="material-checkbox"></label>
                  </div>';

                    return $html;
                })

                ->addColumn('username', function ($row) {

                    return $row->name;

                })
                ->addColumn('name', function ($row) {
                    if ($row->movie_id != null) {
                        $movie = Movie::find($row->movie_id);
                        return $name = $movie->title;
                    } else {
                        $tv = Tvseries::find($row->tv_series_id);
                        return $name = $tv->title;
                    }

                })
                ->addColumn('comment', function ($row) {

                    return $row->comment;

                })
                ->addColumn('status', function ($row) {

                    if ($row->status == 1) {
                        return "<a href=" . route('quick.comment.status', $row->id) . " class='btn btn-sm btn-success'>" . __('adminstaticwords.Approved') . "</a>";
                    } else {
                        return "<a href=" . route('quick.comment.status', $row->id) . " class='btn btn-sm btn-danger'>" . __('adminstaticwords.UnApproved') . "</a>";
                    }

                })
                ->addColumn('created_at', function ($row) {
                    return date('F d, Y', strtotime($row->created_at));

                })

                ->addColumn('action', function ($row) {

                    $btn = ' <div class="admin-table-action-block">
                 <button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#deleteModal' . $row->id . '"><i class="material-icons">delete</i> </button></div>';
                    $btn .= '<div id="deleteModal' . $row->id . '" class="delete-modal modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <div class="delete-icon"></div>
                        </div>
                        <div class="modal-body text-center">
                          <h4 class="modal-heading">' . __('adminstaticwords.AreYouSure') . '</h4>
                          <p>' . __('adminstaticwords.DeleteWarrning') . '</p>
                        </div>
                        <div class="modal-footer">
                          <form method="POST" action="' . route("comments.destroy", $row->id) . '">
                            ' . method_field("DELETE") . '
                            ' . csrf_field() . '
                              <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">' . __('adminstaticwords.No') . '</button>
                              <button type="submit" class="btn btn-danger">' . __('adminstaticwords.Yes') . '</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>';

                    return $btn;
                })
                ->rawColumns(['checkbox', 'username', 'name', 'comment', 'status', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.comment.index', compact('comment'));
    }

    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $query = MovieComment::find($id);
        if (isset($query)) {
            $query->delete();

            return back()->with('deleted', __('Comment has been deleted!'));
        } else {
            return back()->with('deleted', __('Comment not found!'));
        }

    }

    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), ['checked' => 'required']);

        if ($validator->fails()) {

            return back()->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $query = MovieComment::findOrFail($checked);
            if (isset($query->subcomment)) {
                foreach ($query->subcomment as $sub) {
                    $sub->delete();
                }
            }
            $query->delete();
        }

        return back()->with('deleted',__('Comment has been deleted'));
    }

    public function subcommentindex(Request $request)
    {
        $subcomment = MovieSubcomment::get();
        if ($request->ajax()) {
            return \Datatables::of($subcomment)

                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $html = '<div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="' . $row->id . '" id="checkbox' . $row->id . '">
                    <label for="checkbox' . $row->id . '" class="material-checkbox"></label>
                  </div>';

                    return $html;
                })

                ->addColumn('username', function ($row) {
                    $user = User::find($row->user_id);
                    return $user->name;

                })

                ->addColumn('comment', function ($row) {

                    return $row->comment->comment;

                })

                ->addColumn('reply', function ($row) {

                    return $row->reply;

                })
                ->addColumn('status', function ($row) {

                    if ($row->status == 1) {
                        return "<a href=" . route('quick.subcomment.status', $row->id) . " class='btn btn-sm btn-success'>" . __('adminstaticwords.Approved') . "</a>";
                    } else {
                        return "<a href=" . route('quick.subcomment.status', $row->id) . " class='btn btn-sm btn-danger'>" . __('adminstaticwords.UnApproved') . "</a>";
                    }

                })
                ->addColumn('created_at', function ($row) {
                   
                    return date('F d, Y', strtotime($row->created_at));

                })

                ->addColumn('action', function ($row) {

                    $btn = ' <div class="admin-table-action-block">
                 <button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#deleteModal' . $row->id . '"><i class="material-icons">delete</i> </button></div>';
                    $btn .= '<div id="deleteModal' . $row->id . '" class="delete-modal modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <div class="delete-icon"></div>
                        </div>
                        <div class="modal-body text-center">
                          <h4 class="modal-heading">' . __('adminstaticwords.AreYouSure') . '</h4>
                          <p>' . __('adminstaticwords.DeleteWarrning') . '</p>
                        </div>
                        <div class="modal-footer">
                          <form method="POST" action="' . route("subcomments.destroy", $row->id) . '">
                            ' . method_field("DELETE") . '
                            ' . csrf_field() . '
                              <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">' . __('adminstaticwords.No') . '</button>
                              <button type="submit" class="btn btn-danger">' . __('adminstaticwords.Yes') . '</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>';

                    return $btn;
                })
                ->rawColumns(['checkbox', 'username', 'comment', 'status', 'reply', 'created_at', 'action'])
                ->make(true);
        }
        return view('admin.comment.sub_index', compact('subcomment'));
    }

    public function subcommentdestroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $query = MovieSubcomment::find($id);
        if (isset($query)) {
            $query->delete();

            return back()->with('deleted', __('SubComment has been deleted!'));
        } else {
            return back()->with('deleted', __('SubComment not found!'));
        }

    }

    public function sub_bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), ['checked' => 'required']);

        if ($validator->fails()) {

            return back()
                ->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $query = MovieSubcomment::findOrFail($checked);

            $query->delete();
        }

        return back()->with('deleted', __('SubComment has been deleted'));
    }
}
