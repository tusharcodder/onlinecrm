<?php

namespace App\Http\Controllers;

use App\Binding;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;

class BindingController extends Controller 
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:binding-list|binding-create|binding-edit|binding-delete', ['only' => ['index','store']]);
		$this->middleware('permission:binding-list', ['only' => ['index']]);
		$this->middleware('permission:binding-create', ['only' => ['create','store']]);
		$this->middleware('permission:binding-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:binding-delete', ['only' => ['destroy']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$search = $request->input('search');
        //
		$bindings = Binding::where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $bindings->appends ( array (
			'search' => $search
		));
		
        return view('bindings.index',compact('bindings','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		return view('bindings.create');
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
		$user = Auth::user();
		$uid = $user->id;

        //
		$request->validate([ // validation
			'name' => 'required|unique:bindings,name',
		]);

		// save value in db
		$binding = Binding::create([
			'name' => $request->input('name'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);
	
		return redirect()->route('bindings.index')
                        ->with('success','Binding created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Binding  $binding
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$binding = Binding::find($id);
        return view('bindings.show',compact('binding'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Binding  $binding
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		$binding = Binding::find($id);
        return view('bindings.edit',compact('binding'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Binding  $binding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
		
        $request->validate([ // for validation
			'name' => 'required|unique:bindings,name,'.$id,
		]);
		
		// update value in db
		$binding = Binding::find($id);
        $binding->name = $request->input('name');
        $binding->updated_by = $uid;
        $binding->save();

		return redirect()->route('bindings.index')
                        ->with('success','Binding updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Binding  $binding
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		//
		DB::table("bindings")->where('id',$id)->delete();
        return redirect()->route('bindings.index')
                        ->with('success','Binding deleted successfully.');
    }
}
