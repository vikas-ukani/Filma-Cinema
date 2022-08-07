<?php

namespace App\Http\Controllers;
use App\BannerAdd;
use App\BannerAddMenu;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class BannerAdvertismentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:banneradd.view', ['only' => ['index']]);
        $this->middleware('permission:banneradd.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banneradd.edit', ['only' => ['edit', 'update', 'status_update']]);
        $this->middleware('permission:banneradd.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index()
    {
        
        $banneradd = BannerAdd::select('id','link', 'image', 'is_active','column', 'position', 'created_at', 'updated_at')->orderBy('id', 'DESC')->paginate(12);

        return view('admin.banneradd.index', compact('banneradd'));

    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();
        return view('admin.banneradd.create', compact('menus'));
    }

    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate([
            'link' => 'required',
            'menu' => 'required',
            'position'=>'required',
        ], [
            'menu.required' => __('Please select atleast one menu'),
        ]);

        $input = $request->all();
        $input['link'] = $request->link;
        $input['position'] = $request->position;

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

                $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                $img = Image::make($file->path());
                $img->resize(300, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/banneradd') . '/' . $thumbnail);

                $input['image'] = $thumbnail;
            }

        }
      

        if (isset($input['is_active']) && $input['is_active'] == '1') {
            $input['is_active'] = 1;
        } else {
            $input['is_active'] = 0;
        }

        if (isset($input['column']) && $input['column'] == '1') {
            $input['column'] = 1;
        } else {
            $input['column'] = 0;
        }

        if (isset($input['detail_page']) && $input['detail_page'] == '1') {
            $input['detail_page'] = 1;
        } else {
            $input['detail_page'] = 0;
        }

        $menus = null;

        try {
            $banneradd = BannerAdd::create($input);

            if (isset($request->menu) && count($request->menu) > 0) {
                $menus = $request->menu;
                for ($i = 0; $i < sizeof($menus); $i++) {
                    if ($menus[$i] == 100) {
                        unset($menus);
                        $men = Menu::all();
                        foreach ($men as $key => $value) {
                            # code...
                            $menus[] = $value->id;
                        }
                        DB::table('banner_add_menus')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'banneradd_id' => $banneradd->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );

                    } else {

                        DB::table('banner_add_menus')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'banneradd_id' => $banneradd->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );
                    }

                }

            }

            return back()->with('added', __('Add has been added'));
        } catch (\Exception $e) {

            return back()->with('deleted', $e->getMessage())->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banneradd = BannerAdd::findOrFail($id);
        $menus = Menu::all();
        return view('admin.banneradd.edit', compact('banneradd', 'menus'));
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
            'link' => 'required',
            'column'=>'required',
            'position'=>'required',
        ]);

        $banneradd = BannerAdd::findOrFail($id);
        $input = $request->all();
        foreach ($banneradd->banneradd_menu as $key => $bam) {
            # code...
            $bam->delete();
        }

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

                if ($banneradd->image != null) {

                    $image_file = @file_get_contents(public_path() . '/images/banneradd/' . $banneradd->image);

                    if ($image_file) {
                        unlink(public_path() . '/images/banneradd/' . $banneradd->image);
                    }

                }

                $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                $img = Image::make($file->path());

                $img->resize(300, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/banneradd') . '/' . $thumbnail);
                $input['image'] = $thumbnail;
            }

        }

        

        if (isset($request->is_active)) {
            $input['is_active'] = '1';
        } else {

            $input['is_active'] = '0';
        }

        if (isset($request->column)) {
            $input['column'] = '1';
        } else {

            $input['column'] = '0';
        }

        if (isset($request->detail_page)) {
            $input['detail_page'] = '1';
        } else {

            $input['detail_page'] = '0';
        }


        try {
            $banneradd->update($input);
            if (isset($request->menu) && count($request->menu) > 0) {
                $menus = $request->menu;
                for ($i = 0; $i < sizeof($menus); $i++) {
                    if ($menus[$i] == 100) {
                        unset($menus);
                        $men = Menu::all();
                        foreach ($men as $key => $value) {
                            # code...
                            $menus[] = $value->id;
                        }
                        DB::table('banner_add_menus')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'banneradd_id' => $banneradd->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );

                    } else {

                        DB::table('banner_add_menus')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'banneradd_id' => $banneradd->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );
                    }

                }

            }

            return redirect('admin/banneradd')->with('updated', __('Post has been updated'));
        } catch (\Exception $e) {

            return back()->with('deleted', $e->getMessage())->withInput();
        }
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
        $banneradd = BannerAdd::findOrFail($id);
        if ($banneradd->image != null) {
            $content = @file_get_contents(public_path() . '/images/banneradd' . $banneradd->image);
            if ($content) {
                unlink(public_path() . "/images/banneradd" . $banneradd->image);
            }
        }
        $banneradd_menu = BannerAddMenu::where('banneradd_id', $id)->delete();
        $banneradd->delete();

        return back()->with('deleted', __('Post has been deleted'));
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
            $banneradd = BannerAdd::findOrFail($checked);
            if ($banneradd->image != null) {
                $content = @file_get_contents(public_path() . '/images/banneradd' . $banneradd->image);
                if ($content) {
                    unlink(public_path() . "/images/banneradd" . $banneradd->image);
                }
            }
            BannerAddMenu::where('banneradd_id', $checked)->delete();

            $banneradd->delete();
        }
        return back()->with('deleted', __('Post has been deleted'));
    }
}
