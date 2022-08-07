<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class LanguageController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:site-settings.language', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'bulk_delete']]);

    }

    public function index(Request $request)
    {
        $langs = DB::table('languages')->select('id', 'local', 'def', 'created_at', 'name')->get();

        if ($request->ajax()) {
            return DataTables::of($langs)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $html = '<div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="' . $row->id . '" id="checkbox' . $row->id . '">
                    <label for="checkbox' . $row->id . '" class="material-checkbox"></label>
                  </div>';

                    return $html;
                })

                ->addColumn('created_at', function ($row) {
                    return date('F d, Y', strtotime($row->created_at));

                })
                ->addColumn('def', function ($row) {
                    if ($row->def == 1) {
                        return '<i class="text-success fa fa-check"></i>';
                    } else {
                        return '<i class="text-danger fa fa-times"></i>';
                    }

                })

                ->addColumn('action', function ($row) {
                    $btn = ' <div class="admin-table-action-block">
                    <a href="' . route('languages.edit', $row->id) . '" data-toggle="tooltip" data-original-title="' . __('adminstaticwords.Edit') . '" class="btn-info btn-floating"><i class="material-icons">mode_edit</i></a>
                    <a href="' . url('languages/' . $row->local . '/translations') . '" data-toggle="tooltip" data-original-title="update static word translation" class="btn-success btn-floating"><i class="material-icons">settings</i></a>
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
                              <form method="POST" action="' . route("languages.destroy", $row->id) . '">
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
                ->rawColumns(['checkbox', 'created_at', 'action', 'def'])
                ->make(true);
        }

        return view('admin.language.index', compact('langs'));
    }

    public function customstatic(Request $request)
    {
        $langs = DB::table('languages')->select('id', 'local', 'def', 'created_at', 'name')->get();
        return view('admin.language.customstatic', compact('langs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.language.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'local' => 'required|unique:languages,local',
            'name' => 'required'
        ]);


        
        $input = $request->all();

        $all_def = Language::where('def','=',1)->get();

        if (isset($request->def)) {

            foreach ($all_def as $value) {
                $remove_def =  Language::where('id','=',$value->id)->update(['def' => 0]);
            }

             $input['def'] = 1;

        }else{
            if($all_def->count()<1)
            {
                return back()->with('delete','Atleast one language need to set default !');
            }

            $input['def'] = 0;
        }


        if (!is_dir(base_path() . '/resources/lang/' . $request->local)) {
            mkdir(base_path() . '/resources/lang/' . $request->local);
            copy(base_path() . '/resources/lang/en/staticwords.php', base_path() . '/resources/lang/' . $request->local . '/staticwords.php');
            copy(base_path() . '/resources/lang/en/adminstaticwords.php', base_path() . '/resources/lang/' . $request->local . '/adminstaticwords.php');
        }
        if (is_dir(base_path() . '/resources/lang/')) {
            copy(resource_path() . '/lang/en.json', resource_path() . '/lang/' . $request->local . '.json');
        }
        if ($request->rtl) {
            $input['rtl'] = 1;
        } else {
            $input['rtl'] = 0;
        }
        Language::create($input);

        Session::flash('success', trans('flash.AddedSuccessfully'));
        return redirect('admin/lang');
        //return back()->with('added', __('Language has been added'));
    }

    public function showlang() 
    {
        $langs = Language::all();
        return view('admin.language.index', compact('langs'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $langs = Language::findOrFail($id);
        return view('admin.language.edit', compact('langs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $findlang = Language::find($id);
        $request->validate([
            'local' => 'required|unique:languages,local,' . $findlang->id,
            'name' => 'required|unique:languages,name,' . $findlang->id,
        ]);

        $input = $request->all();

        if (isset($findlang)) {

            if (isset($request->def)) {

                $deflang = Language::where('def', '=', 1)->where('id', '!=', $id)->first();

                if (isset($deflang)) {
                    $deflang->def = 0;
                    $deflang->save();
                }

                $input['def'] = 1;

                Session::put('changed_language', $findlang->local);

            } else {

                if ($findlang->def == 1) {
                    $input['def'] = 1;
                } else {
                    $input['def'] = 0;
                }

            }

            if ($request->rtl) {
                $input['rtl'] = 1;
            } else {
                $input['rtl'] = 0;
            }

            $findlang->update($input);

            return back()->with("updated",__('Language Details Updated !'));
        } else {
            return back()->with("deleted", __('404 Language Not found !'));
        }
        return back()->with('updated', __('Language has been updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $language = Language::findOrFail($id);
        if ($language->def == 1) {
            return back()->with('deleted', __('Default Language cannot be deleted'));

        } else {

            $language->delete();
            return back()->with('deleted', __('Language has been deleted'));
        }

    }

    public function bulk_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('deleted', __('Please select one of them to delete'));
        }

        $lang = Language::findMany($request->checked)->each(function($l){
            if($l->def !== 1){
                $l->delete();
            }
        });

        return back()->with('deleted', __('Languages have been deleted'));

    }

    public function staticword($local)
    {
        // return $local;
        $findlang = Language::where('local', '=', $local)->first();

        if (isset($findlang))
        {

            if (file_exists(resource_path() .'/lang/' . $findlang->local . '/staticwords.php'))
            {
                $file = file_get_contents(resource_path() ."/lang/$findlang->local/staticwords.php");
                return view('admin.language.staticword', compact('findlang', 'file'));
            }
            else
            {

                if (is_dir(resource_path() .'/lang/' . $findlang->local))
                {
                    copy(resource_path() . "/lang/en/staticwords.php", resource_path().'/lang/' . $findlang->local . '/staticwords.php');

                    
                    $file = file_get_contents(resource_path(). "/lang/$findlang->local/staticwords.php");
                    return view('admin.language.staticword', compact('findlang', 'file'));
                }
                else
                {
                    mkdir(resource_path() .'/lang/' . $findlang->local);
                    copy(resource_path() ."/lang/en/staticwords.php", resource_path() .'/lang/' . $findlang->local . '/staticwords.php');
                    $file = file_get_contents(resource_path() ."/lang/$findlang->local/staticwords.php");
                    return view('admin.language.staticword', compact('findlang', 'file'));
                }

            }

        }
        else
        {
            return back()
                ->with('delete', trans('flash.NotFound'));
        }
    }

    public function frontupdate(Request $request, $local)
    {
        $findlang = Language::where('local', '=', $local)->first();
        if (isset($findlang))
        {

            $transfile = $request->transfile;
            file_put_contents(resource_path() .'/lang/' . $findlang->local . '/staticwords.php', $transfile . PHP_EOL);
            return back()->with('updated', trans('flash.UpdatedSuccessfully'));

        }
        else
        {
            return back()
                ->with('delete', trans('flash.NotFound'));
        }
    }

    public function adminstaticword($local)
    {
        $findlang = Language::where('local', '=', $local)->first();

        if (isset($findlang))
        {

            if (file_exists(resource_path() .'/lang/' . $findlang->local . '/adminstaticwords.php'))
            {
                $file = file_get_contents(resource_path() ."/lang/$findlang->local/adminstaticwords.php");
                return view('admin.language.adminstatic', compact('findlang', 'file'));
            }
            else
            {

                if (is_dir(resource_path() .'/lang/' . $findlang->local))
                {
                    copy(resource_path() ."/lang/en/adminstaticwords.php", resource_path() .'/lang/' . $findlang->local . '/adminstaticword.php');
                    $file = file_get_contents(resource_path() ."/lang/$findlang->local/adminstaticwords.php");
                    return view('admin.language.adminstatic', compact('findlang', 'file'));
                }
                else
                {
                    mkdir(resource_path() .'/lang/' . $findlang->local);
                    copy(resource_path() ."/lang/en/adminstaticwords.php", resource_path() .'/lang/' . $findlang->local . '/adminstaticword.php');
                    $file = file_get_contents(resource_path() ."/lang/$findlang->local/adminstaticwords.php");
                    return view('admin.language.adminstatic', compact('findlang', 'file'));
                }

            }

        }
        else
        {
            return back()
                ->with('delete', trans('flash.NotFound'));
        }
    }

    public function adminupdate(Request $request, $local)
    {
        $findlang = Language::where('local', '=', $local)->first();
        if (isset($findlang))
        {

            $transfile = $request->transfile;
            file_put_contents(resource_path() .'/lang/' . $findlang->local . '/adminstaticwords.php', $transfile . PHP_EOL);
            return back()->with('updated', trans('flash.UpdatedSuccessfully'));

        }
        else
        {
            return back()
                ->with('delete', trans('flash.NotFound'));
        }
    }


}
