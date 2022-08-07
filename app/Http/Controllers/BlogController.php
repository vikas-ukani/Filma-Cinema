<?php

namespace App\Http\Controllers;

use App\Blog;
use App\BlogMenu;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class BlogController extends Controller
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:blog.view', ['only' => ['index']]);
        $this->middleware('permission:blog.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:blog.edit', ['only' => ['edit', 'update', 'status_update']]);
        $this->middleware('permission:blog.delete', ['only' => ['destroy', 'bulk_delete']]);
    }

    public function index(Request $request)
    {
        if ($request->search != null) {
            $blogs = Blog::where('title', 'like', '%' . $request->search . '%')->select('id', 'title', 'image', 'is_active', 'detail', 'created_at', 'updated_at')->orderBy('id', 'DESC')->paginate(12);
        } else {
            $blogs = Blog::select('id', 'title', 'image', 'is_active', 'detail', 'created_at', 'updated_at')->orderBy('id', 'DESC')->paginate(12);
        }

        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('admin.blog.create', compact('menus'));
    }

    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $request->validate([
            'title' => 'required',
            'detail' => 'required',
            'menu' => 'required',
        ], [
            'menu.required' => __('Please select atleast one menu'),
        ]);

        $input = $request->all();
        $input['detail'] = $request->detail;

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
                $img->save(public_path('/images/blog') . '/' . $thumbnail);

                $input['image'] = $thumbnail;
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
                $img = Image::make($file->path());

                $img->resize(300, 169, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/blog') . '/' . $poster);

                $input['poster'] = $poster;
            }

        }

        if (isset($input['is_active']) && $input['is_active'] == '1') {
            $input['is_active'] = 1;
        } else {
            $input['is_active'] = 0;
        }

        $slug = str_slug($input['title'], '-');
        $input['slug'] = $slug;
        $auth = Auth::user()->id;
        $input['user_id'] = $auth;

        $menus = null;

        try {
            $blog = Blog::create($input);

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
                        DB::table('blog_menu')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'blog_id' => $blog->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );

                    } else {

                        DB::table('blog_menu')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'blog_id' => $blog->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );
                    }

                }

            }

            return back()->with('added', __('Post has been added'));
        } catch (\Exception $e) {

            return back()->with('deleted', $e->getMessage())->withInput();
        }

    }

    public function showBlogList()
    {
        $auth = Auth::user();
        $blogs = Blog::orderBy('created_at', 'desc')->where('is_active', '1')->get();
        return view('blog', compact('blogs', 'auth'));
    }

    public function showBlog($slug)
    {
        $blogdetail = Blog::where('slug', $slug)->first();
        $exceptblog = Blog::where('slug', '!=', $slug)->orderBy('id','DESC')->get();
        return view('blogdetail', compact('blogdetail', 'exceptblog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $menus = Menu::all();
        return view('admin.blog.edit', compact('blog', 'menus'));

    }

/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Product  $id
 * @return \Illuminate\Http\Response
 */

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $request->validate([
            'title' => 'required|min:3|unique:blogs,title,' . $id,
            'detail' => 'required|min:3',
        ]);

        $blog = Blog::findOrFail($id);
        $input = $request->all();
        $input['detail'] = $request->detail;
        foreach ($blog->blog_m as $key => $bm) {
            # code...
            $bm->delete();
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

                if ($blog->image != null) {

                    $image_file = @file_get_contents(public_path() . '/images/blog/' . $blog->image);

                    if ($image_file) {
                        unlink(public_path() . '/images/blog/' . $blog->image);
                    }

                }

                $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                $img = Image::make($file->path());

                $img->resize(300, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/blog') . '/' . $thumbnail);
                $input['image'] = $thumbnail;
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

                if ($blog->poster != null) {

                    $image_file = @file_get_contents(public_path() . '/images/blog' . $blog->poster);

                    if ($image_file) {
                        unlink(public_path() . '/images/blog' . $blog->image);
                    }

                }

                $poster = 'poster_' . time() . $file->getClientOriginalName();
                $img = Image::make($file->path());

                $img->resize(300, 169, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/blog') . '/' . $poster);

                $input['poster'] = $poster;
            }

        }

        if (isset($request->is_active)) {
            $input['is_active'] = '1';
        } else {

            $input['is_active'] = '0';
        }

        $slug = str_slug($input['title'], '-');

        $input['slug'] = $slug;

        try {
            $blog->update($input);
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
                        DB::table('blog_menu')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'blog_id' => $blog->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );

                    } else {

                        DB::table('blog_menu')->insert(
                            array(
                                'menu_id' => $menus[$i],
                                'blog_id' => $blog->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            )
                        );
                    }

                }

            }

            return redirect('admin/blog')->with('updated', __('Post has been updated'));
        } catch (\Exception $e) {

            return back()->with('deleted', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $blog = Blog::findOrFail($id);
        if ($blog->image != null) {
            $content = @file_get_contents(public_path() . '/images/blog' . $blog->image);
            if ($content) {
                unlink(public_path() . "/images/blog" . $blog->image);
            }
        }
        if ($blog->poster != null) {
            $content = @file_get_contents(public_path() . '/images/blog' . $blog->poster);
            if ($content) {
                unlink(public_path() . "/images/blog" . $blog->poster);
            }
        }
        $blog_menu = BlogMenu::where('blog_id', $id)->delete();
        $blog->delete();

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
            $blog = Blog::findOrFail($checked);
            if ($blog->image != null) {
                $content = @file_get_contents(public_path() . '/images/blog' . $blog->image);
                if ($content) {
                    unlink(public_path() . "/images/blog" . $blog->image);
                }
            }
            if ($blog->poster != null) {
                $content = @file_get_contents(public_path() . '/images/blog' . $blog->poster);
                if ($content) {
                    unlink(public_path() . "/images/blog" . $blog->poster);
                }
            }
            BlogMenu::where('blog_id', $checked)->delete();

            $blog->delete();
        }
        return back()->with('deleted', __('Post has been deleted'));
    }

}
