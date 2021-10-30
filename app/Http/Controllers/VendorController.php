<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:vendor-list|vendor-create|vendor-edit|vendor-delete', ['only' => ['index','store']]);
		$this->middleware('permission:vendor-list', ['only' => ['index']]);
		$this->middleware('permission:vendor-create', ['only' => ['create','store']]);
		$this->middleware('permission:vendor-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:vendor-delete', ['only' => ['destroy']]);
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
        
		$vendors = Vendor::select('vendors.*')->where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%')						
						->orWhere('number','LIKE','%'.$search.'%')
						->orWhere('email','LIKE','%'.$search.'%')
						->orWhere('address','LIKE','%'.$search.'%');

				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $vendors->appends ( array (
			'search' => $search
		));
		
        return view('vendors.index',compact('vendors','search'))
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
		 return view('vendors.create');
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
			'vendor_name' => 'required|unique:vendors,name',			
		]);
		
		// save value in db
		$vendor = Vendor::create([
								'name' => $request->input('vendor_name'),
								'address' => $request->input('address'),
								'number' => $request->input('cphone'),
								'email' => $request->input('cemail'),
								'created_by' => $uid,
								'updated_by' => $uid
							]);
				
		return redirect()->route('vendorss.index')
                        ->with('success','Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		//
		$vendor = Vendor::find($id);
        return view('vendors.show',compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get data
		$vendor = Vendor::find($id);
        return view('vendors.edit',compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
		
        //		
		$request->validate([
			'vendor_name' => 'required|unique:vendors,name,'.$id,				
		]);		
			
		// update value in db
		$vendor = Vendor::find($id);       
        $vendor->name = $request->input('vendor_name');      
        $vendor->number = $request->input('cphone');
        $vendor->email = $request->input('cemail');  
		$vendor->address = $request->input('address');        
        $vendor->updated_by = $uid;
        $vendor->save();
		return redirect()->route('vendorss.index')
                        ->with('success','Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		DB::table("vendors")->where('id',$id)->delete();
        return redirect()->route('vendorss.index')
                        ->with('success','Vendor deleted successfully.');
    }
}
