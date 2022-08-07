<?php

namespace App\Http\Controllers;

use App\LandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class LandingPageController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('permission:front-settings.landing-page', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'bluk_delete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $landing_pages = LandingPage::orderBy('position', 'asc')->get();
        return view('admin.landing-page.index', compact('landing_pages'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.landing-page.create');
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
        $input = $request->all();

        if ($file = $request->file('image')) {
            $name = 'landing_page_' . time() . $file->getClientOriginalName();
            $img = Image::make($file->path());

            $img->resize(1440, 675, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('/images/main-home') . '/' . $name);
          
            $input['image'] = $name;
        }

        if (!isset($input['button'])) {
            $input['button'] = 0;
        }

        if (!isset($input['left'])) {
            $input['left'] = 0;
        }

        if (!isset($input['button_link'])) {
            $input['button_link'] = 'register';
        } else {
            $input['button_link'] = 'login';
        }

        $input['position'] = (LandingPage::count() + 1);

        LandingPage::create($input);

        return back()->with('added', __('Landing page block has been added'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LandingPage  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $landing_page = LandingPage::findOrFail($id);
        return view('admin.landing-page.edit', compact('landing_page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LandingPage  $landingPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $input = $request->all();
        $landing_page = LandingPage::findOrFail($id);

        if ($file = $request->file('image')) {
            $name = 'landing_page_' . time() . $file->getClientOriginalName();
            if ($landing_page->image != null) {
                $content = @file_get_contents(public_path() . '/images/main-home/' . $landing_page->image);
                if ($content) {
                    unlink(public_path() . '/images/main-home/' . $landing_page->image);
                }
            }
            $img = Image::make($file->path());
            $img->resize(1440, 675);

            $img->save(public_path('/images/main-home') . '/' . $name);
            $input['image'] = $name;
        }

        if (!isset($input['button'])) {
            $input['button'] = 0;
        }

        if (!isset($input['left'])) {
            $input['left'] = 0;
        }

        if (!isset($input['button_link'])) {
            $input['button_link'] = 'register';
        } else {
            $input['button_link'] = 'login';
        }

        $landing_page->update($input);

        return redirect('admin/customize/landing-page')->with('updated', __('Landing page block has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $landing_page = LandingPage::findOrFail($id);

        if ($landing_page->image != null) {
            $content = @file_get_contents(public_path() . '/images/main-home/' . $landing_page->image);
            if ($content) {
                unlink(public_path() . '/images/main-home/' . $landing_page->image);
            }
        }
        $landing_page->delete();
        return back()->with('deleted', __('Landing page block has been deleted'));
    }

    public function reposition(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if ($request->item != null) {
            $items = explode('&', $request->item);
            $all_ids = collect();
            foreach ($items as $key => $value) {
                $all_ids->push(substr($value, 7));
            }

            $i = 0;

            foreach ($all_ids as $id) {
                $i++;
                $item = LandingPage::findOrFail($id);
                $item->position = $i;
                $item->save();
            }

            return response()->json(['success' => true]);

        } else {
            return response()->json(['success' => false]);
        }
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
            $block = LandingPage::findOrFail($checked);

            if ($block->image != null) {
                $content = @file_get_contents(public_path() . '/images/main-home/' . $block->image);
                if ($content) {
                    unlink(public_path() . '/images/main-home/' . $block->image);
                }
            }

            $block->delete();
        }

        return back()->with('deleted', __('Landing page blocks has been deleted'));
    }
}
