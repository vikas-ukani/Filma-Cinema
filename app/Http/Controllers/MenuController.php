<?php

namespace App\Http\Controllers;

use App\Button;
use App\Genre;
use App\Menu;
use App\MenuGenreShow;
use App\MenuSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class MenuController extends Controller
{
  
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:menu.view', ['only' => ['index']]);
        $this->middleware('permission:menu.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:menu.edit', ['only' => ['edit', 'update', 'reposition']]);
        $this->middleware('permission:menu.delete', ['only' => ['destroy', 'bulk_delete']]);
    }
    public function index(Request $request)
    {
        $menus = Menu::select('id', 'name', 'slug', 'created_at', 'updated_at')->OrderBy('position', 'ASC')->get();

        if ($request->ajax()) {
            return DataTables::of($menus)
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row->id;
                    },
                ])
                ->setRowClass('row1 sortable')
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

                ->addColumn('action', 'admin.menu.action')
                ->rawColumns(['checkbox', 'name', 'action', 'created_at', 'updated_at'])
                ->make(true);
        }

        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_genre = Genre::OrderBy('position', 'ASC')->get();
        $topsection = Button::first()->is_toprated;
        return view('admin.menu.create', compact('all_genre', 'topsection'));
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
            'logo' => 'mimes:png,jpeg,bmp,jpg',
        ]);

        if (!isset($request->section)) {
            $request->validate([
                'section' => 'required',
            ],
                [
                    'section.required' => __('Atleast one section should be checked !'),
                ]
            );
        }

        $input = $request->all();

        $input['position'] = (Menu::count() + 1);

        $input['slug'] = str_slug(strtolower($request->name), '-');

        $menudone = Menu::create($input);

        if ($menudone) {

            foreach ($request->section as $key => $value) {
                if (isset($value)) {
                    $ms = new MenuSection;
                    $ms->menu_id = $menudone->id;
                    $ms->section_id = $value;
                    if (isset($request->limit[$key])) {
                        $ms->item_limit = $request->limit[$key];
                    } else {
                        $ms->item_limit = null;
                    }
                    if (isset($request->view[$key])) {
                        $ms->view = $request->view[$key];
                    } else {
                        $ms->view = 1;
                    }
                    if (isset($request->view[$key])) {
                        $ms->order = $request->order[$key];
                    } else {
                        $ms->order = 1;
                    }
                    $ms->save();
                    if ($ms) {
                        if ($value == 2 && $request->genre_id != null) {
                            foreach ($request->genre_id as $genre) {
                                $ms_show = new MenuGenreShow;
                                $ms_show->menu_id = $menudone->id;
                                $ms_show->menu_section_id = $ms->section_id;
                                $ms_show->genre_id = $genre;

                                $ms_show->save();
                            }

                        }
                    }
                }
            }

        }

        return back()->with('added', __('Menu has been created !'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $menu = Menu::findOrFail($id);
        $select_genre = MenuGenreShow::where('menu_id', $menu->id)->get();
        $all_genre = Genre::OrderBy('position', 'ASC')->get();
        $topsection = Button::first()->is_toprated;

        return view('admin.menu.edit', compact('menu', 'select_genre', 'all_genre', 'topsection'));
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
            'name' => 'required',
        ]);

        if (!isset($request->section)) {
            $request->validate([
                'section' => 'required',
            ],
                [
                    'section.required' => __('Atleast one section should be checked !'),
                ]
            );
        }

        $menu = Menu::findOrFail($id);

        $input = $request->all();
        $input['slug'] = str_slug(strtolower($request->name), '-');

        $menudone = $menu->update($input);

        if (isset($menu->menusections)) {
            foreach ($menu->menusections as $section) {
                $section->delete();
            }
        }
        if (isset($menu->menugenreshow)) {

            $menu->menugenreshow()->delete();
        }

        if ($menudone) {

            foreach ($request->section as $key => $value) {
                if (isset($value)) {
                    $ms = new MenuSection;
                    $ms->menu_id = $menu->id;
                    $ms->section_id = $value;
                    if (isset($request->limit[$key])) {
                        $ms->item_limit = $request->limit[$key];
                    } else {
                        $ms->item_limit = null;
                    }
                    if (isset($request->view[$key])) {
                        $ms->view = $request->view[$key];
                    } else {
                        $ms->view = 1;
                    }
                    if (isset($request->view[$key])) {
                        $ms->order = $request->order[$key];
                    } else {

                        $ms->order = 1;
                    }
                    $ms->save();
                    if ($ms) {
                        if ($value == 2 && $request->genre_id != null) {

                            foreach ($request->genre_id as $genre) {

                                MenuGenreShow::firstOrCreate(
                                    [
                                        'menu_id' => $menu->id,
                                        'menu_section_id' => $ms->section_id,
                                        'genre_id' => $genre,
                                    ]
                                );

                            }

                        }
                    }
                }
            }

        }

        return redirect('admin/menu')->with('updated', __('Menu has been updated'));
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
        $menu = Menu::findOrFail($id);
        try {
            if ($menu != null) {
                if (isset($menu->menusections)) {
                    $menu->menusections()->delete();
                }
                if (isset($menu->menugenreshow)) {
                   
                    $menu->menugenreshow()->delete();
                }
            }
            $menu->delete();
            return back()->with('deleted', __('Menu has been deleted'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    public function reposition(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if($request->ajax()){

            $posts = Menu::all();
            foreach ($posts as $post) {
                foreach ($request->order as $order) {
                    if ($order['id'] == $post->id) {
                        \DB::table('menus')->where('id',$post->id)->update(['position' => $order['position']]);
                    }
                }
            }
            return response()->json('Update Successfully.', 200);

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
            $menu = Menu::findOrFail($checked);
            if ($menu != null) {
                if (isset($menu->menusections)) {
                    $menu->menusections()->delete();
                }
                if (isset($menu->menugenreshow)) {

                    $menu->menugenreshow()->delete();
                }
            }

            $menu->delete();
        }
        return back()->with('deleted', __('Menus has been deleted'));
    }

}
