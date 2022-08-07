<?php

namespace App\Http\Controllers;

use App\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class LabelController extends Controller
{
  
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:label.view', ['only' => ['index']]);
        $this->middleware('permission:label.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:label.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:label.delete', ['only' => ['destroy', 'bulk_delete']]);
    }
    public function index(Request $request)
    {
        $labels = Label::select('id', 'name', 'created_at', 'updated_at')->get();
        if ($request->ajax()) {
            return \Datatables::of($labels)

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

                ->addColumn('action', 'admin.label.action')
                ->rawColumns(['checkbox', 'name', 'created_at', 'action', 'updated_at'])
                ->make(true);
        }
        return view('admin.label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.label.create');
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
            'name' => 'required|unique:labels,name',
        ]);

        $input = $request->all();
        $query = new Label();
        $query->name = $input['name'];

        try {
            $query->save();
            return back()->with('added', __('Label created successfully !'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lable  $lable
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $label = Label::find($id);
        return view('admin.label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lable  $lable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $query = Label::find($id);
        $request->validate([
            'name' => 'required|unique:labels,name,' . $query->id,
        ]);

        $input = $request->all();

        $query->name = $input['name'];
        try {
            $query->save();
            return redirect('admin/label')->with('updated', __('Label updated Successfully !'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lable  $lable
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $query = Label::find($id);
        if (isset($query) && $query != null) {
            $query->delete();
            return back()->with('deleted', __('Label has been deleted !'));
        } else {
            return back()->with('deleted', $e->getMessage());
        }
    }

    public function bulk_delete(Request $request)
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

            $label = Label::findOrFail($checked);

            $label->delete();
        }

        return back()->with('deleted', __('Label has been deleted'));
    }
}
