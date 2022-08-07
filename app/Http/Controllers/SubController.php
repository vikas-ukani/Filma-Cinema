<?php

namespace App\Http\Controllers;

use App\Package;
use App\sub;
use Illuminate\Http\Request;


class SubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub = Sub::all();
        return view('admin.Seo', compact('sub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $package = Package::pluck('name', 'id')->all();
        return view('admin.userplan.create', compact('package'));
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
            'email' => 'required|email|unique:users',

        ]);

        $input = $request->all();

        Sub::create($input);
        return redirect('admin/users')->with('added', __('Active Plan has been created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\sub  $sub
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub = Sub::findOrFail($id);
        return view('admin.users.edit', compact('sub'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sub  $sub
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'plan' => 'required',
        ]);

        $input = $request->all();

        $sub->update($input);
        return redirect('admin/users')->with('added', __('Active Plan has been created'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sub  $sub
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $sub = Sub::findOrFail($id);
        $input = $request->all();
        $sub->update($input);
        return back()->with('updated',__('Active Plan has been updated'));
    }
}
