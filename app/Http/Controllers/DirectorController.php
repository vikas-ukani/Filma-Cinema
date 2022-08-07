<?php

namespace App\Http\Controllers;

use App\Director;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class DirectorController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:director.view', ['only' => ['index']]);
        $this->middleware('permission:director.create', ['only' => ['create', 'store', 'ajaxstore']]);
        $this->middleware('permission:director.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:director.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index(Request $request)
    {
        if ($request->search != null) {
            $directors = Director::where('name', 'like', '%' . $request->search . '%')->select('id', 'name', 'image', 'biography', 'place_of_birth', 'slug')->paginate(12);
        } else {
            $directors = Director::select('id', 'name', 'image', 'biography', 'place_of_birth', 'slug')->paginate(12);
        }

        return view('admin.director.index', compact('directors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.director.create');
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
            'name' => 'required',
        ]);

        $input = $request->all();

        if ($file = $request->file('image')) {
            $validator = Validator::make(
                [
                    'image' => $request->image,
                    'extension' => strtolower($request->image->getClientOriginalExtension()),
                ],
                [
                    'image' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp',
                ]
            );
            if ($validator->fails()) {
                return back()->with('deleted', __('Invalid file format Please use jpg,webp,jpeg and png image format !'))->withInput();
            } else {
                $name = "director_" . time() . $file->getClientOriginalName();
                $file->move('images/directors', $name);
                $input['image'] = $name;
            }
        }

        $slug = str_slug($request['name'], '-');
        $input['slug'] = $slug;
        Director::create($input);
        return back()->with('added', __('Director has been created'));
    }

    public function ajaxstore(Request $request)
    {
        $input = $request->all();

        if ($file = $request->file('image')) {
            $name = "director_" . time() . $file->getClientOriginalName();
            $file->move('images/directors', $name);
            $input['image'] = $name;
        }

        $slug = str_slug($request['name'], '-');
        $input['slug'] = $slug;
        $result = Director::create($input);

        if ($result) {
            return response()->json(['msg' => __('Director created succesfully !')]);
        } else {
            return response()->json(['msg' => __('Please try again !')]);
        }
    }

    public function listofd(Request $request)
    {

        if (!isset($request->searchTerm)) {
            $fetchData = Director::select('id', 'name')->get();
        } else {
            $search = $request->searchTerm;
            $fetchData = Director::where('name', 'LIKE', '%' . $search . '%')->select('id', 'name')->get();
        }

        $data = array();

        foreach ($fetchData as $row) {
            $data[] = array("id" => $row['id'], "text" => $row['name']);
        }

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $director = Director::find($id);
        return view('admin.director.edit', compact('director'));
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
        $director = Director::find($id);

        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();

        if ($file = $request->file('image')) {
            $validator = Validator::make(
                [
                    'image' => $request->image,
                    'extension' => strtolower($request->image->getClientOriginalExtension()),
                ],
                [
                    'image' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp',
                ]
            );
            if ($validator->fails()) {
                return back()->with('deleted', __('Invalid file format Please use jpg,webp,jpeg and png image format !'))->withInput();
            } else {
                $name = "director_" . time() . $file->getClientOriginalName();
                if ($director->image != null) {
                    $content = @file_get_contents(public_path() . '/images/directors/' . $director->image);
                    if ($content) {
                        unlink(public_path() . "/images/directors/" . $director->image);
                    }
                }
                $file->move('images/directors', $name);
                $input['image'] = $name;
            }
        }
        $slug = str_slug($request['name'], '-');
        $input['slug'] = $slug;
        $director->update($input);
        return redirect('admin/directors')->with('updated', __('Director has been updated'));
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
        $director = Director::findOrFail($id);

        if ($director->image != null) {
            $content = @file_get_contents(public_path() . '/images/directors/' . $director->image);
            if ($content) {
                unlink(public_path() . "/images/directors/" . $director->image);
            }
        }
        $director->delete();
        return redirect('admin/directors')->with('deleted', __('Director has been deleted'));
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

            $director = Director::findOrFail($checked);

            if ($director->image != null) {
                $content = @file_get_contents(public_path() . '/images/directors/' . $director->image);
                if ($content) {
                    unlink(public_path() . "/images/directors/" . $director->image);
                }
            }

            Director::destroy($checked);
        }

        return back()->with('deleted',__('Directors has been deleted'));
    }

    public function importdirectors(Request $request)
    {

        $validator = Validator::make(
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls,csv',
            ]

        );

        if ($validator->fails()) {

            return back()->with('deleted', 'Invalid file !');
        }

        $filename = 'actors_' . time() . '.' . $request->file->getClientOriginalExtension();

        Storage::disk('local')->put('/excel/' . $filename, file_get_contents($request->file->getRealPath()));

        $directors = fastexcel()->import(storage_path() . '/app/excel/' . $filename);

        if (count($directors)) {

            $directors->each(function ($item) {

                Director::create([

                    'name' => $item['name'],
                    'image' => $item['image'] != null ? $item['image'] : null,
                    'biography' => $item['biography'] != null ? $item['biography'] : null,
                    'place_of_birth' => $item['place_of_birth'] != null ? $item['place_of_birth'] : null,
                    'DOB' => $item['DOB'] != null ? $item['DOB'] : null,

                ]);

            });

            unlink(storage_path() . '/app/excel/' . $filename);

            return back()->with('added', __('Directors imported successfully'));

        } else {

            return back()->with('deleted', __('File is empty !'));
        }

    }
}
