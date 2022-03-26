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
    function __construct()
    {
         $this->middleware('permission:warehouse-list|warehouse-create|warehouse-edit|warehouse-delete', ['only' => ['index','store']]);
         $this->middleware('permission:warehouse-list', ['only' => ['index']]);
         $this->middleware('permission:warehouse-create', ['only' => ['create','store']]);
         $this->middleware('permission:warehouse-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:warehouse-delete', ['only' => ['destroy']]);         
    }
	
   
    public function index(Request $request)
    {
        //
		$search = $request->input('search');
		$warehouses = Warehouse::where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%')					
					->orWhere('country_code','LIKE','%'.$search.'%');
				})->orderBy('is_shipped','DESC')->paginate(10)->setPath('');
		
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
			'name' => ['required',Rule::unique('warehouses','name')],			
			'country_code' => 'required'		
		]);
		
		$is_shipped = $request->input('is_shipped');
		if($is_shipped)
			$is_shipped=1;
		else
			$is_shipped=0;
		
        if(strtoupper($request->input('country_code'))=='IN'){
            return redirect()->route('warehouse.index')
                        ->with('error','You can not create warehouse in India.');
        }
		// save value in db
		Warehouse::create([			
			'name' => $request->input('name'),			
			'country_code' => strtoupper($request->input('country_code')),
			'is_shipped' =>	$is_shipped,
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
        $warehouse = Warehouse::find($id);
		return view('warehouses.edit',compact('warehouse'));
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
			'country_code' => 'required'			
		]);
		
        if( strtoupper($request->input('country_code'))=='IN'&& $id!=1){
            return redirect()->route('warehouse.index')
                        ->with('error','You can not create warehouse in India.');
        }
		
		$is_shipped = $request->input('is_shipped');
		if($is_shipped)
			$is_shipped=1;
		else
			$is_shipped=0;
		
		// update value in db
		$Warehouse = Warehouse::find($id);	    
        $Warehouse->name = $request->input('name');       
        $Warehouse->country_code = strtoupper($request->input('country_code')); $Warehouse->is_shipped =	$is_shipped;
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
