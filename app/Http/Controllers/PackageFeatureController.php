<?php

namespace App\Http\Controllers;

use App\PackageFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

 
class PackageFeatureController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:package-feature.view', ['only' => ['index']]);
        $this->middleware('permission:package-feature.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:package-feature.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:package-feature.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index(Request $request)
    {
        $p_feature = PackageFeature::select('id', 'name', 'created_at', 'updated_at')->get();

        if ($request->ajax()) {
            return DataTables::of($p_feature)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $html = '<div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="' . $row->id . '" id="checkbox' . $row->id . '">
                    <label for="checkbox' . $row->id . '" class="material-checkbox"></label>
                  </div>';

                    return $html;
                })
                ->addColumn('name', function ($row) {

                    return $row->name;

                })

                ->addColumn('created_at', function ($row) {
                    return date('F d, Y', strtotime($row->created_at));

                })
                ->addColumn('updated_at', function ($row) {
                    return date('F d, Y', strtotime($row->updated_at));

                })

                ->addColumn('action', 'admin.package_feature.action')
                ->rawColumns(['checkbox', 'name', 'created_at', 'action', 'updated_at'])
                ->make(true);
        }

        return view('admin.package_feature.index', compact('p_feature'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package_feature.create');
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
            'name' => 'required',
        ]);
        $input = $request->all();
        PackageFeature::create($input);
        return back()->with('added', __('Package feature has been added'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PackageFeature  $packageFeature
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $p_feature = PackageFeature::findOrFail($id);
        return view('admin.package_feature.edit', compact('p_feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackageFeature  $packageFeature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate([
            'name' => 'required',
        ]);
        $p_feature = PackageFeature::findOrFail($id);
        $input = $request->all();
        $p_feature->update($input);
        return redirect('/admin/package_feature')->with('updated', __('Package feature has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PackageFeature  $packageFeature
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $p_feature = PackageFeature::findOrFail($id);
        $p_feature->delete();
        return back()->with('deleted', __('Package feature has been deleted'));
    }

    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {
            PackageFeature::destroy($checked);
        }

        return back()->with('deleted', __('Package Feature has been deleted'));
    }
}
