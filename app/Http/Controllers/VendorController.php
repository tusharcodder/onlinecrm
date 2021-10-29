<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;

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
        //
		$vendors = Vendor::where(function($query) use ($search) {
					$query->where('type','LIKE','%'.$search.'%')
						->orWhere('vendor_name','LIKE','%'.$search.'%')	
						->orWhere('contact_person_name','LIKE','%'.$search.'%')
						->orWhere('contact_person_number','LIKE','%'.$search.'%')
						->orWhere('contact_person_email','LIKE','%'.$search.'%')
						->orWhere('commission_type','LIKE','%'.$search.'%')
						->orWhere('commission','LIKE','%'.$search.'%');
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
        //
		$type = ['Aggregator', 'Online', 'SOR', 'Outride'];
		return view('vendors.create', compact('type'));
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
		if($request->input('type') != 'Aggregator'){ // not for Aggregator vendor validation
			$request->validate([
				'type' => 'required',
				'vendor_name' => [
					'required',
					Rule::unique('vendors')->where(function ($query) use($request){
						return $query->where('type', $request->input('type'));
					}),
				],
				'cname' => 'required',
				'cemail' => ['required', new Emails],
				'cphone' => 'required',
				'ctype' => 'required',
				'commission' => 'required|min:0|max:100',
			]);
		}else{
			$request->validate([ // for Aggregator vendor validation
				'type' => 'required',
				'vendor_name' => [
					'required',
					Rule::unique('vendors')->where(function ($query) use($request){
						return $query->where('type', $request->input('type'));
					}),
				],
				'cname' => 'required',
				'cemail' => ['required', new Emails],
				'cphone' => 'required',
				'ctype' => 'required',
				'commission' => 'required|min:0|max:100',
				'addmore.*.vname' => 'required',
				'addmore.*.vcomm' => 'required|min:0|max:100',
			],
			[
				'addmore.*.vname.required' => 'Each vendor must have a name.',
				'addmore.*.vcomm.required' => 'Each vendor must have a commission.',
			]);
		}

		// save value in db
		$vendor = Vendor::create(['type' => $request->input('type'),'vendor_name' => $request->input('vendor_name'),'contact_person_name' => $request->input('cname'),'contact_person_number' => $request->input('cphone'),'contact_person_email' => $request->input('cemail'),'commission_type' => $request->input('ctype'),'commission' => $request->input('commission'),'created_by' => $uid,'updated_by' => $uid]);
		
		// last insert id of vendor
		$vendorid= $vendor->id;
		
		// save aggregator vendor info into table aggregator_has_vendors table
		if(!empty($request->input('addmore'))){
			foreach($request->input('addmore') as $val){
				if(!empty($val['vname'])){
					$data[] = [
						'vendor_id' => $vendorid,
						'aggregator_vendor_name' => $val['vname'],
						'aggregator_vendor_commission' => $val['vcomm'],
					];
				}
			}
			if(!empty($data))
				DB::table('aggregator_has_vendors')->insert($data);
		}
		
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
        $aggregatordetails = Vendor::select('aggregator_has_vendors.*')->join("aggregator_has_vendors","aggregator_has_vendors.vendor_id","=","vendors.id")
            ->where("vendors.id",$id)
            ->get();


        return view('vendors.show',compact('vendor','aggregatordetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		$type = ['Aggregator', 'Online', 'SOR', 'Outride'];
		$vendor = Vendor::find($id);
        $aggregatordetails = Vendor::select('aggregator_has_vendors.aggregator_vendor_name as vname', 'aggregator_has_vendors.aggregator_vendor_commission as vcomm', 'aggregator_has_vendors.id as vid')->join("aggregator_has_vendors","aggregator_has_vendors.vendor_id","=","vendors.id")
            ->where("vendors.id",$id)
            ->get()->toArray();

        return view('vendors.edit',compact('type', 'vendor', 'aggregatordetails'));
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
		if($request->input('type') != 'Aggregator'){ // not for Aggregator vendor validation
			$request->validate([
				'type' => 'required',
				'vendor_name' => [
					'required',
					Rule::unique('vendors')->where(function ($query) use($request){
						return $query->where('type', $request->input('type'));
					})->ignore($id),
				],
				'cname' => 'required',
				'cemail' => ['required', new Emails],
				'cphone' => 'required',
				'ctype' => 'required',
				'commission' => 'required|min:0|max:100',
			]);
		}else{
			$request->validate([ // for Aggregator vendor validation
				'type' => 'required',
				'vendor_name' => [
					'required',
					Rule::unique('vendors')->where(function ($query) use($request){
						return $query->where('type', $request->input('type'));
					})->ignore($id),
				],
				'cname' => 'required',
				'cemail' => ['required', new Emails],
				'cphone' => 'required',
				'ctype' => 'required',
				'commission' => 'required|min:0|max:100',
				'addmore.*.vname' => 'required',
				'addmore.*.vcomm' => 'required|min:0|max:100',
			],
			[
				'addmore.*.vname.required' => 'Each vendor must have a name.',
				'addmore.*.vcomm.required' => 'Each vendor must have a commission.',
			]);
		}
		
		// update value in db
		$vendor = Vendor::find($id);
        $vendor->type = $request->input('type');
        $vendor->vendor_name = $request->input('vendor_name');
        $vendor->contact_person_name = $request->input('cname');
        $vendor->contact_person_number = $request->input('cphone');
        $vendor->contact_person_email = $request->input('cemail');
        $vendor->commission_type = $request->input('ctype');
        $vendor->commission = $request->input('commission');
        $vendor->updated_by = $uid;
        $vendor->save();
		
		// last insert id of vendor
		$vendorid= $id;
		
		// save aggregator vendor info into table aggregator_has_vendors table
		if(!empty($request->input('addmore'))){
			// delete all records from aggregator has vendors
			DB::table("aggregator_has_vendors")->where('vendor_id',$id)->delete();
			
			foreach($request->input('addmore') as $val){
				if(!empty($val['vname'])){
					$data[] = [
						'vendor_id' => $vendorid,
						'aggregator_vendor_name' => $val['vname'],
						'aggregator_vendor_commission' => $val['vcomm'],
					];
				}
			}
			if(!empty($data))
				DB::table('aggregator_has_vendors')->insert($data);
		}
		
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
