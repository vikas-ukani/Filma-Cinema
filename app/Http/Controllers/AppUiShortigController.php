<?php

namespace App\Http\Controllers;

use App\AppUiShorting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class AppUiShortigController extends Controller
{
    public function index(Request $request)
    {
       $appUiShorting = AppUiShorting::select('id', 'name','position','is_active')->OrderBy('position', 'ASC')->get();
        // echo $appUiShorting;
        if ($request->ajax()) {
            return DataTables::of($appUiShorting)
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row->id;
                    },
                ])
                ->setRowClass('row1 sortable')
                ->make(true);
        }

        return view('admin.appUiShorting.index', compact('appUiShorting'));
    }

    public function appmenustatus(Request $request, $id)
    {
        $asu = AppUiShorting::findOrFail($id);
        $asu->is_active = $request->is_active;
        $asu->save();

        if ($request->is_active == 1) {
            return back()->with('updated', __('Status has been to active!'));
        } else {
            return back()->with('updated',__('Status has been to deactive!'));
        }

    }

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $appUiShorting = AppUiShorting::findOrFail($id);
        $input = $request->all();

        $appUiShorting->update([
            'is_active' => $input['is_active'],

        ]);

        return back()->with('updated', __('App Ui Shorting Setting has been updated'));

    }

    public function reposition(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if($request->ajax()){

            $posts = AppUiShorting::all();
            foreach ($posts as $post) {
                foreach ($request->order as $order) {
                    if ($order['id'] == $post->id) {
                        \DB::table('app_ui_shortings')->where('id',$post->id)->update(['position' => $order['position']]);
                    }
                }
            }
            return response()->json('Update Successfully.', 200);

        }

       
    }
//full_detail_table //app_ui_reposition
}
