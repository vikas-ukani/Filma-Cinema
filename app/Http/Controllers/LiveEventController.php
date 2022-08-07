<?php

namespace App\Http\Controllers;

use App\LiveEvent;
use App\Menu;
use App\MenuVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class LiveEventController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:liveevent.view', ['only' => ['index']]);
        $this->middleware('permission:liveevent.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:liveevent.edit', ['only' => ['edit', 'update', 'status_update']]);
        $this->middleware('permission:liveevent.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index(Request $request)
    {
        if ($request->search != null) {
            $liveevent = DB::table('live_events')->where('title', 'like', '%' . $request->search . '%')->select('id', 'title', 'thumbnail', 'poster', 'organized_by', 'slug', 'start_time', 'end_time', 'description')->where('status', '1')->orderBy('id', 'DESC')->paginate(12);
        } else {
            $liveevent = DB::table('live_events')->select('id', 'title', 'thumbnail', 'poster', 'organized_by', 'slug', 'start_time', 'end_time', 'description')->where('status', '1')->orderBy('id', 'DESC')->paginate(12);
        }

        return view('admin.liveevent.index', compact('liveevent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $menus = Menu::all();

        $all_liveevent = LiveEvent::where('status', '1')->get();

        return view('admin.liveevent.create', compact('menus', 'all_liveevent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $menus = null;

        if (isset($request->menu) && count($request->menu) > 0) {
            $menus = $request->menu;
        }

        $newevent = new LiveEvent;

        $input = $request->all();

        if (isset($request->status)) {
            $input['status'] = 1;
        } else {
            $input['status'] = 0;
        }

        $thumbnail = null;
        $poster = null;

        if ($file = $request->file('thumbnail')) {
            $validator = Validator::make(
                [
                    'thumbnail' => $request->thumbnail,
                    'extension' => strtolower($request->thumbnail->getClientOriginalExtension()),
                ],
                [
                    'thumbnail' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp',
                ]
            );
            if ($validator->fails()) {
                return back()->with('deleted', __('Invalid file format Please use jpg,webp,jpeg and png image format !'))->withInput();
            } else {
                $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                if ($request->thumbnail != null) {
                    $content = @file_get_contents(public_path() . '/images/events/thumbnails/' . $request->thumbnail);
                    if ($content) {
                        unlink(public_path() . "/images/events/thumbnails/" . $request->thumbnail);
                    }
                }
                $img = Image::make($file->path());

                $img->resize(300, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/events/thumbnails') . '/' . $thumbnail);

                $input['thumbnail'] = $thumbnail;
            }
        }

        if ($file = $request->file('poster')) {
            $validator = Validator::make(
                [
                    'poster' => $request->poster,
                    'extension' => strtolower($request->poster->getClientOriginalExtension()),
                ],
                [
                    'poster' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp',
                ]
            );
            if ($validator->fails()) {
                return back()->with('deleted', __('Invalid file format Please use jpg,webp,jpeg and png image format !'))->withInput();
            } else {
                $poster = 'poster_' . time() . $file->getClientOriginalName();
                if ($request->poster != null) {
                    $content = @file_get_contents(public_path() . '/images/events/posters/' . $request->poster);
                    if ($content) {
                        unlink(public_path() . "/images/events/posters/" . $request->poster);
                    }
                }

                $img = Image::make($file->path());

                $img->resize(300, 169, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/events/posters') . '/' . $poster);
                $input['poster'] = $poster;
            }
        }

        $input['start_time'] = date('Y-m-d H:i:s', strtotime($request->start_time));
        $input['end_time'] = date('Y-m-d H:i:s', strtotime($request->end_time));

        $description = $request->description;
        $slug = str_slug($input['title'], '-');
        $input['slug'] = $slug;

        if ($request->selecturl == "iframeurl") {

            $input['iframeurl'] = $request->iframeurl;
            $input['type'] = 'iframeurl';
            $input['readyurl'] = null;

        } else if ($request->selecturl == "customurl") {

            $input['iframeurl'] = null;
            $input['type'] = 'readyurl';
            $input['readyurl'] = $request->ready_url;

        }

        $created_liveevents = LiveEvent::create($input);

        if ($menus != null) {
            if (count($menus) > 0) {
                foreach ($menus as $key => $value) {
                    MenuVideo::create([
                        'menu_id' => $value,
                        'live_event_id' => $created_liveevents->id,
                    ]);
                }
            }
        }

        return back()->with('added', __('LiveEvent has been added'));
    }

/**
 * Display the specified resource.
 *
 * @param  int  $url
 * @return \Illuminate\Http\Response
 */

/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function edit($id)
    {

        $menus = Menu::all();

        $liveevent = LiveEvent::findOrFail($id);

        // get old audio language values

        return view('admin.liveevent.edit', compact('menus', 'liveevent'));
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
        $liveevent = LiveEvent::findOrFail($id);

        $menus = null;

        if (isset($request->menu) && count($request->menu) > 0) {
            $menus = $request->menu;
        }

        if (!isset($input['status'])) {
            $input['status'] = 0;
        }

        $input = $request->all();

        $slug = str_slug($input['title'], '-');
        $input['slug'] = $slug;

        $input['start_time'] = date('Y-m-d H:i:s', strtotime($request->start_time));

        $input['end_time'] = date('Y-m-d H:i:s', strtotime($request->end_time));

        $thumbnail = null;
        $poster = null;

        $description = $request->description;

        if ($file = $request->file('thumbnail')) {
            $validator = Validator::make(
                [
                    'thumbnail' => $request->thumbnail,
                    'extension' => strtolower($request->thumbnail->getClientOriginalExtension()),
                ],
                [
                    'thumbnail' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp',
                ]
            );
            if ($validator->fails()) {
                return back()->with('deleted', __('Invalid file format Please use jpg,webp,jpeg and png image format !'))->withInput();
            } else {
                $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                if ($liveevent->thumbnail != null) {
                    $content = @file_get_contents(public_path() . '/images/events/thumbnails/' . $liveevent->thumbnail);

                    if ($content != null) {

                        unlink(public_path() . "/images/events/thumbnails/" . $liveevent->thumbnail);
                    }
                }
                $img = Image::make($file->path());

                $img->resize(300, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/events/thumbnails') . '/' . $thumbnail);

                $input['thumbnail'] = $thumbnail;
            }
        }

        if ($file = $request->file('poster')) {
            $validator = Validator::make(
                [
                    'poster' => $request->poster,
                    'extension' => strtolower($request->poster->getClientOriginalExtension()),
                ],
                [
                    'poster' => 'required',
                    'extension' => 'required|in:jpg,jpeg,png,webp',
                ]
            );
            if ($validator->fails()) {
                return back()->with('deleted', __('Invalid file format Please use jpg,webp,jpeg and png image format !'))->withInput();
            } else {
                $poster = 'poster_' . time() . $file->getClientOriginalName();
                if ($liveevent->poster != null) {
                    $content = @file_get_contents(public_path() . '/images/events/posters/' . $liveevent->poster);
                    if ($content) {
                        unlink(public_path() . "/images/events/posters/" . $liveevent->poster);
                    }
                }
                $img = Image::make($file->path());

                $img->resize(300, 169, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/events/posters') . '/' . $poster);

                $input['poster'] = $poster;
            }
        }

        if ($request->selecturl == "iframeurl") {

            $input['iframeurl'] = $request->iframeurl;
            $input['ready_url'] = null;
            $input['type'] = 'iframeurl';

        } else {

            $input['iframeurl'] = null;
            $input['ready_url'] = $request->ready_url;
            $input['type'] = 'readyurl';

        }

        $liveevent->update($input);

        if ($menus != null) {
            if (count($menus) > 0) {
                if (isset($liveevent->menus) && count($liveevent->menus) > 0) {
                    foreach ($liveevent->menus as $key => $value) {
                        $value->delete();
                    }
                }
                foreach ($menus as $key => $value) {
                    MenuVideo::create([
                        'menu_id' => $value,
                        'live_event_id' => $liveevent->id,
                    ]);
                }
            }
        } else {
            if (isset($liveevent->menus) && count($liveevent->menus) > 0) {
                foreach ($liveevent->menus as $key => $value) {
                    $value->delete();
                }
            }
        }

        return redirect('/admin/liveevent')->with('updated', __('LiveEvent has been updated'));
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
        $liveevent = LiveEvent::findOrFail($id);

        if ($liveevent->thumbnail != null) {
            $content = @file_get_contents(public_path() . '/images/events/thumbnails/' . $liveevent->thumbnail);
            if ($content) {
                unlink(public_path() . "/images/events/thumbnails/" . $liveevent->thumbnail);
            }
        }
        if ($liveevent->poster != null) {
            $content = @file_get_contents(public_path() . '/images/events/posters/' . $liveevent->poster);
            if ($content) {
                unlink(public_path() . "/images/events/posters/" . $liveevent->poster);
            }
        }

        $liveevent->delete();

        return back()->with('deleted',__('LiveEvent has been deleted'));
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

            return back()->with('deleted', __('Please check one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $liveevent = LiveEvent::findOrFail($checked);

            if ($liveevent->thumbnail != null) {
                $content = @file_get_contents(public_path() . '/images/events/thumbnails/' . $liveevent->thumbnail);
                if ($content) {
                    unlink(public_path() . "/images/events/thumbnails/" . $liveevent->thumbnail);
                }
            }
            if ($liveevent->poster != null) {
                $content = @file_get_contents(public_path() . '/images/events/posters/' . $liveevent->poster);
                if ($content) {
                    unlink(public_path() . "/images/events/posters/" . $liveevent->poster);
                }
            }

            LiveEvent::destroy($checked);
        }

        return back()->with('deleted', __('LiveEvent has been deleted'));
    }

    public function importliveevent(Request $request)
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

        $filename = 'liveevent_' . time() . '.' . $request->file->getClientOriginalExtension();

        Storage::disk('local')->put('/excel/' . $filename, file_get_contents($request->file->getRealPath()));

        $liveevents = fastexcel()->import(storage_path() . '/app/excel/' . $filename);

        if (count($liveevents)) {

            $liveevents->each(function ($item) {

                DB::beginTransaction();

                try {
                    $liveevent = LiveEvent::create([

                        'title' => $item['title'] != null ? $item['title'] : null,
                        'slug' => str_slug($item['title'], '-'),
                        'description' => $item['description'] != null ? $item['description'] : null,
                        'thumbnail' => $item['thumbnail'] != null ? $item['thumbnail'] : null,
                        'poster' => $item['poster'] != null ? $item['poster'] : null,
                        'type' => $item['type'] != null ? $item['type'] : null,
                        'iframeurl' => $item['iframeurl'] != null ? $item['iframeurl'] : null,
                        'ready_url' => $item['ready_url'] != null ? $item['ready_url'] : null,
                        'start_time' => $item['start_time'] != null ? $item['start_time'] : null,
                        'end_time' => $item['end_time'] != null ? $item['end_time'] : null,
                        'organized_by' => $item['organized_by'] != null ? $item['organized_by'] : null,
                        'status' => 1,

                    ]);

                    if (isset($item['menu']) && $item['menu'] != null) {
                        $menus_ids = explode(',', $item['menu']);

                        foreach ($menus_ids as $value) {

                            MenuVideo::create(['menu_id' => $value, 'live_event_id' => $liveevent->id]);
                        }
                    }

                    DB::commit();

                } catch (\Exception $e) {
                    return back()->with('deleted', $e->getMessage());
                }

            });

            unlink(storage_path() . '/app/excel/' . $filename);

            return back()->with('added', __('Live Event imported successfully'));

        } else {

            return back()->with('deleted', __('File is empty !'));
        }

    }

}
