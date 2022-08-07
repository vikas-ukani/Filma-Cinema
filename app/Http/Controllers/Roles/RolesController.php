<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

/*==========================================
    =            Author: Media City            =
    Author URI: https://mediacity.co.in
    =            Author: Media City            =
    =            Copyright (c) 2022            =
    ==========================================*/
class RolesController extends Controller
{
   

    public function index(Request $request)
    {   

         abort_if(!auth()->user()->can('roles.view'),403,'User does not have the right permissions.');

        $roles = DB::table('roles')->select('roles.id', 'roles.name')->orderBy('id','ASC');
        

        $roles = Role::orderby('id', 'ASC')->get();

        

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        abort_if(!auth()->user()->can('roles.create'),403,'User does not have the right permissions.');
        

        $role_permission = Permission::select('name','id')->groupBy('name')->get();

        $custom_permission = array();

        foreach($role_permission as $per){

            $key = substr($per->name, 0, strpos($per->name, ".")); 

            if(str_starts_with($per->name, $key)){
                $custom_permission[$key][] = $per;
            }
            
        }
        

        return view('roles.create',compact('custom_permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        abort_if(!auth()->user()->can('roles.create'),403,'User does not have the right permissions.');
        
        if(env('DEMO_LOCK') == 1){
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }
        $request->validate([
            'name' => 'required|unique:roles,name'
        ],
        [
            'name.required' => __('Role name is required !'),
            'name.unique'   => __('Role name already taken !')
        ]
        );

        $role = Role::create(['name' => $request->name]);

        if($request->permissions){
            foreach ($request->permissions as $key => $value) {
                $role->givePermissionTo($value);
            }
        }
        return redirect(route('roles.index'))->with('added',__('Role has been created !'));
    }

   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
         abort_if(!auth()->user()->can('roles.edit'),403,'User does not have the right permissions.');

        if(in_array($id,['1','2','3'])){
            return redirect(route('roles.index'))->with('deleted',__('System role cannot be edit!'));
        }
        

        $role = Role::with('permissions')->find($id);

        $role_permission = Permission::select('name','id')->get();

        $custom_permission = array();

        foreach($role_permission as $per){

            $key = substr($per->name, 0, strpos($per->name, ".")); 

            if(str_starts_with($per->name, $key)){
                $custom_permission[$key][] = $per;
            }
            
        }
       
         return view('roles.edit',compact('role_permission','role','custom_permission'));
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
        if(env('DEMO_LOCK') == 1){
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }
        
         abort_if(!auth()->user()->can('roles.edit'),403,'User does not have the right permissions.');
        
        if(in_array($id,['1','2','3'])){
            return redirect(route('roles.index'))->with('deleted',__('System role cannot be edit'));
        }

        $role = Role::find($id);

        $request->validate([
            'name' => 'required|unique:roles,name,'.$id
        ],
        [
            'name.required' => __('Role name is required !'),
            'name.unique'   => __('Role name already taken !')
        ]
        );

        $role->name = $request->name;

        $role->save();

        $role->syncPermissions($request->permissions);

        //return back()->with('updated',__('Role has been updated !'));
        return redirect(route('roles.index'))->with('added',__('Role has been updated !'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(env('DEMO_LOCK') == 1){
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }
        

        Role::where('id',$id)->delete();
       
        return back()->with('deleted',__('Role has been deleted !'));
    }

    public function createPermission(Request $request){
        if(env('DEMO_LOCK') == 1){
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }
        Permission::create([
            'name' => $request->name,
        ]);
    
        echo __("Created");
    
        return back();
    }

    public function bulkPermission(Request $request){
        if(env('DEMO_LOCK') == 1){
            return back()->with('deleted',__('This action is disabled in the demo !'));
        }
        Permission::create([
            'name' => $request->name.'.view',
        ]);

        Permission::create([
            'name' => $request->name.'.create',
        ]);

        Permission::create([
            'name' => $request->name.'.edit',
        ]);

        Permission::create([
            'name' => $request->name.'.delete',
        ]);

        echo __("Created");
    
        return back();

    }
}
