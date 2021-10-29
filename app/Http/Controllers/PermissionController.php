<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use DB;

class PermissionController extends Controller
{
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		///
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
	
		//
		$permissions = Permission::where('name','LIKE','%'.$search.'%')->orWhere('name','LIKE','%'.$search.'%')->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $permissions->appends ( array (
			'search' => $search
		));
		
        return view('permissions.index',compact('permissions','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
		$this->validate($request, [
            'name' => 'required|unique:permissions,name'
        ]);

		// clear spatie cache
		app()['cache']->forget('spatie.permission.cache');
        $permission = Permission::create(['name' => $request->input('name')]);
		
        return redirect()->route('permissions.index')
                        ->with('success','Permissions created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$permission = Permission::find($id);
        $rolePermissions = Permission::select('roles.name')->join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
		->join("roles","roles.id","=","role_has_permissions.role_id")
            ->where("role_has_permissions.permission_id",$id)
            ->get();
			
		$userPermissions = Permission::select('users.name')->join("model_has_permissions","model_has_permissions.permission_id","=","permissions.id")
		->join("users","users.id","=","model_has_permissions.model_id")
            ->where("model_has_permissions.permission_id",$id)
            ->get();

        return view('permissions.show',compact('permission','rolePermissions','userPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $permission = Permission::find($id);
        return view('permissions.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$this->validate($request, [
            'name' => 'required|unique:permissions,name,'.$id,
        ]);
		
		// clear spatie cache
		app()['cache']->forget('spatie.permission.cache');
        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->save();

        return redirect()->route('permissions.index')
                        ->with('success','Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		DB::table("permissions")->where('id',$id)->delete();
        return redirect()->route('permissions.index')
                        ->with('success','Permission deleted successfully.');
    }
}