<?php

namespace App\Http\Controllers;

use App\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ActorController extends Controller
{
   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:actor.view', ['only' => ['index']]);
        $this->middleware('permission:actor.create', ['only' => ['create', 'store', 'ajaxstore']]);
        $this->middleware('permission:actor.edit', ['only' => ['edit', 'update', 'status_update']]);
        $this->middleware('permission:actor.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index(Request $request)
    {
        if ($request->search != null) {
            $actors = Actor::where('name', 'like', '%' . $request->search . '%')->select('id', 'name', 'image', 'biography', 'place_of_birth', 'slug')->paginate(12);
        } else {
            $actors = Actor::select('id', 'name', 'image', 'biography', 'place_of_birth', 'slug')->paginate(12);
        }

        return view('admin.actor.index', compact('actors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.actor.create');
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

        $actor = new Actor();

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
                $image = "actor_" . time() . $file->getClientOriginalName();
                $file->move('images/actors', $image);
            }

        } else {
            $image = null;
        }

        $this->save($actor, $request, $image);
        return back()->with('added', __('Actor has been created'));
    }

    public function ajaxstore(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $actor = new Actor();
        if ($file = $request->file('image')) {
            $name = "actor_" . time() . $file->getClientOriginalName();
            $file->move('images/actors', $name);

        } else {
            $name = null;
        }

        $result = $this->save($actor, $request, $name);

        if ($result) {
            return response()->json(['msg' => __('Actor created succesfully !')]);
        } else {
            return response()->json(['msg' => __('Please try again !')]);
        }
    }

    public function listofactor(Request $request)
    {

        if (!isset($request->searchTerm)) {
            $fetchData = Actor::select('id', 'name')->get();
        } else {
            $search = $request->searchTerm;
            $fetchData = Actor::where('name', 'LIKE', '%' . $search . '%')->select('id', 'name')->get();
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
        $actor = Actor::find($id);
        return view('admin.actor.edit', compact('actor'));
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

        $actor = Actor::find($id);

        $request->validate([
            'name' => 'required',
        ]);

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
                $image = "actor_" . time() . $file->getClientOriginalName();
                if ($actor->image != null) {
                    $content = @file_get_contents(public_path() . '/images/actors/' . $actor->image);
                    if ($content) {
                        unlink(public_path() . "/images/actors/" . $actor->image);
                    }
                }
                $file->move('images/actors', $image);
            }

        } else {
            if ($actor->image != null) {
                $image = $actor->image;
            } else {
                $image = null;
            }

        }

        $this->save($actor, $request, $image);
        return redirect('admin/actors')->with('updated', __('Actor has been updated !'));
    }

    private function save(Actor $actor, Request $request, $image)
    {
        $slug = str_slug($request['name'], '-');
        $actor->name = strip_tags($request->name);
        $actor->biography = strip_tags($request->biography);
        $actor->image = strip_tags($image);
        $actor->place_of_birth = $request->place_of_birth ? strip_tags($request->place_of_birth) : null;
        $actor->DOB = $request->DOB ? $request->DOB : null;
        $actor->slug = strip_tags($slug);
        $actor->save();
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
        $actor = Actor::findOrFail($id);

        if ($actor->image != null) {
            $content = @file_get_contents(public_path() . '/images/actors/' . $actor->image);
            if ($content) {
                unlink(public_path() . "/images/actors/" . $actor->image);
            }
        }

        $actor->delete();
        return redirect('admin/actors')->with('deleted', __('Actor has been deleted !'));
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

            $actor = Actor::findOrFail($checked);

            if ($actor->image != null) {
                $content = @file_get_contents(public_path() . '/images/actors/' . $actor->image);
                if ($content) {
                    unlink(public_path() . "/images/actors/" . $actor->image);
                }
            }

            Actor::destroy($checked);
        }

        return back()->with('deleted', __('Actors has been deleted!'));
    }

    public function importactors(Request $request)
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

            return back()->with('deleted', __('Invalid file !'));
        }

        $filename = 'actors_' . time() . '.' . $request->file->getClientOriginalExtension();

        Storage::disk('local')->put('/excel/' . $filename, file_get_contents($request->file->getRealPath()));

        $actors = fastexcel()->import(storage_path() . '/app/excel/' . $filename);

        if (count($actors)) {

            $actors->each(function ($item) {

                Actor::create([

                    'name' => strip_tags($item['name']),
                    'image' => $item['image'] != null ? strip_tags($item['image']) : null,
                    'biography' => $item['biography'] != null ? strip_tags($item['biography']) : null,
                    'place_of_birth' => $item['place_of_birth'] != null ? strip_tags($item['place_of_birth']) : null,
                    'DOB' => $item['DOB'] != null ? $item['DOB'] : null,

                ]);

            });

            unlink(storage_path() . '/app/excel/' . $filename);

            return back()->with('added', __('Actors imported successfully'));

        } else {

            return back()->with('deleted', __('File is empty !'));
        }

    }
}
