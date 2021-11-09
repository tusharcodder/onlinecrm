<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SupplierController extends Controller
{
    function __construct()
    {
		$this->middleware('permission:supplier-list|supplier-create|supplier-edit|supplier-delete', ['only' => ['index','store']]);
		$this->middleware('permission:supplier-list', ['only' => ['index']]);
		$this->middleware('permission:supplier-create', ['only' => ['create','store']]);
		$this->middleware('permission:supplier-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
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
        
		$supplier = Supplier::select('suppliers.*')->where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%')						
						->orWhere('number','LIKE','%'.$search.'%')
						->orWhere('email','LIKE','%'.$search.'%')
						->orWhere('address','LIKE','%'.$search.'%');

				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $supplier->appends ( array (
			'search' => $search
		));
		
        return view('shippers.index',compact('supplier','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //call the view
        return view('shippers.create');
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
		
        //check validation
		$request->validate([
			'name' => 'required|unique:suppliers,name',			
		]);

		// save value in db
		$vendor = Supplier::create([
								'name' => $request->input('name'),
								'address' => $request->input('address'),
								'number' => $request->input('cphone'),
								'email' => $request->input('email'),
								'created_by' => $uid,
								'updated_by' => $uid
							]);
				
		return redirect()->route('suppliers.index')
                        ->with('success','Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $supplier = Supplier::find($id);
        return view('shippers.show',compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get data
		$supplier = Supplier::find($id);
        return view('shippers.edit',compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //get user detail
		$user = Auth::user();
		$uid = $user->id;
		
        //	check validation	
		$request->validate([
			'name' => 'required|unique:suppliers,name,'.$id,				
		]);		
			
		// update value in db
		$supplier = Supplier::find($id);       
        $supplier->name = $request->input('name');      
        $supplier->number = $request->input('phone');
        $supplier->email = $request->input('email');  
		$supplier->address = $request->input('address');        
        $supplier->updated_by = $uid;
        $supplier->save();
		return redirect()->route('suppliers.index')
                        ->with('success','Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete the record
        DB::table("suppliers")->where('id',$id)->delete();
        return redirect()->route('suppliers.index')
                        ->with('success','Supplier deleted successfully.');
    }
}
