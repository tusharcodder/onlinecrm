<?php

namespace App\Http\Controllers;

use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$search = $request->input('search');
		$warehouses = Warehouse::where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%');						
				})->orderBy('name','ASC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $warehouses->appends ( array (
			'search' => $search
		));
		
        return view('warehouses.index',compact('warehouses','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
		$uid = $user->id;
		
		// validation
        $request->validate([
			'name' => ['required',Rule::unique('warehouses','name')]			
		]);
		
		// save value in db
		Warehouse::create([			
			'name' => $request->input('name'),			
			'created_by' => $uid,
			'updated_by' => $uid
		]);
		
		return redirect()->route('warehouse.index')
                        ->with('success','Warehouse added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Warehouse::find($id);
		return view('warehouses.show',compact('warehouse'));    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $Warehouse = Warehouse::find($id);
		return view('Warehouses.edit',compact('Warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = Auth::user();
		$uid = $user->id;
		
		// validation
        $request->validate([			
			'name' => ['required',Rule::unique('warehouses','name')->ignore($id)],		
		]);
		
		// update value in db
		$Warehouse = Warehouse::find($id);	    
        $Warehouse->name = $request->input('name');       
        $Warehouse->updated_by = $uid;
        $Warehouse->save();
		
		return redirect()->route('warehouse.index')
                        ->with('success','Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("warehouses")->where('id',$id)->delete();
        return redirect()->route('warehouse.index')
                        ->with('success','Warehouse deleted successfully.');
    }
}
