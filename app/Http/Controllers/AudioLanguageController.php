<?php

namespace App\Http\Controllers;

use App\AudioLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;



class AudioLanguageController extends Controller
{
  
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:audiolanguage.view', ['only' => ['index']]);
        $this->middleware('permission:audiolanguage.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:audiolanguage.edit', ['only' => ['edit', 'update', 'status_update']]);
        $this->middleware('permission:audiolanguage.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index(Request $request)
    {
        if ($request->search != null) {
            $audio_languages = AudioLanguage::where('language', 'like', '%' . $request->search . '%')->select('id', 'language', 'image', 'created_at', 'updated_at')->paginate(12);
        } else {
            $audio_languages = AudioLanguage::select('id', 'language', 'image', 'created_at', 'updated_at')->paginate(12);
        }

        return view('admin.audio_language.index', compact('audio_languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.audio_language.create');
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
            'language' => 'required',
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
                $image = "audiolanguage_" . time() . $file->getClientOriginalName();
                $img = Image::make($file->path());

                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/audiolanguage') . '/' . $image);

            }

        } else {
            $image = null;
        }

        $a_lan = new AudioLanguage();

        $this->save($a_lan, $request, $image);
        return back()->with('added', __('Audio language has been added'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $a_lan = AudioLanguage::findOrFail($id);
        return view('admin.audio_language.edit', compact('a_lan'));
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
        $request->validate([
            'language' => 'required',
        ]);

        $a_lan = AudioLanguage::find($id);

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

                if ($a_lan->image != null) {
                    $content = @file_get_contents(public_path() . '/images/audiolanguage/' . $a_lan->image);
                    if ($content) {
                        unlink(public_path() . "/images/audiolanguage/" . $a_lan->image);
                    }
                }
                $image = "audiolanguage_" . time() . $file->getClientOriginalName();
                $img = Image::make($file->path());

                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/audiolanguage') . '/' . $image);

            }
        } else {
            if ($a_lan->image != null) {
                $image = $a_lan->image;
            } else {
                $image = null;
            }

        }
        $this->save($a_lan, $request, $image);
        return redirect('/admin/audio_language')->with('updated', __('Language has been updated'));
    }

    private function save($a_lan, Request $request, $image)
    {
        $a_lan->language = $request->language;
        $a_lan->status = $request->status ? 1 : 0;
        $a_lan->image = $image ? $image : null;
        $a_lan->save();
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
        $a_lan = AudioLanguage::findOrFail($id);
        if ($a_lan->image != null) {
            $content = @file_get_contents(public_path() . '/images/audiolanguage/' . $a_lan->image);
            if ($content) {
                unlink(public_path() . "/images/audiolanguage/" . $a_lan->image);
            }
        }
        $a_lan->delete();
        return back()->with('deleted', __('Audio Language has been deleted'));
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
            $a_lan = AudioLanguage::findOrFail($checked);
            if ($a_lan->image != null) {
                $content = @file_get_contents(public_path() . '/images/audiolanguage/' . $a_lan->image);
                if ($content) {
                    unlink(public_path() . "/images/audiolanguage/" . $a_lan->image);
                }
            }
            AudioLanguage::destroy($checked);
        }

        return back()->with('deleted', __('Audio Languages has been deleted'));
    }
}
