<?php

namespace App\Http\Controllers; 

use File;
use Session;
use App\UspsZone;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\VendorStockExport;
use App\Imports\VendorStockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;

class UspsZoneController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:vendor-stock-list|vendor-stock-create|vendor-stock-edit|vendor-stock-delete|vendor-stock-import-export', ['only' => ['index','store']]);
         $this->middleware('permission:vendor-stock-list', ['only' => ['index']]);
         $this->middleware('permission:vendor-stock-create', ['only' => ['create','store']]);
         $this->middleware('permission:vendor-stock-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:vendor-stock-delete', ['only' => ['destroy', 'deleteVendorStockAll']]);
         $this->middleware('permission:vendor-stock-import-export', ['only' => ['stockImportExport','import','export']]);
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
		$zone  = UspsZone::select('usps_zone.*','zone_list.zone')
        ->leftjoin('zone_list','zone_list.id','usps_zone.zone_id')
        ->where(function($query) use ($search) {
            $query->where('usps_zone.zone_id','LIKE','%'.$search.'%')
                ->orWhere('usps_zone.zip_code','LIKE','%'.$search.'%');	
                
        })->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $zone->appends ( array (
			'search' => $search
		));
		
        return view('uspszone.index',compact('zone','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zonelist = DB::table('zone_list')->orderBy('zone','asc')->get();
		return view('uspszone.create',compact('zonelist'));
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
		$zone_id = 0;
		// validation
        $request->validate([
			'zip_code' => 'required',
			'zone_name' => 'required',		
		]);
		
        $zone_details = DB::table('zone_list')->where('zone',$request->input('zone_name'))->get();
        if($zone_details->count() > 0)
            $zone_id = $zone_details[0]->id;
        else{//insert new zone in master
            $insertarray = array('zone'=>$request->input('zone_name'),'created_by'=>$uid,'updated_by'=>$uid);
            $zone = Zone::create($insertarray);
            $zone_id = $zone->id;
        }
       // echo $zone_id;exit;
		// save value in db
		UspsZone::create([
			'zone_id' => $zone_id,
			'zip_code' => $request->input('zip_code'),			
			'created_by' => $uid,
			'updated_by' => $uid
		]);
		
		return redirect()->route('uspszone.index')
                        ->with('success','Zone added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = UspsZone::find($id);
		return view('uspszone.show',compact('zone'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $zone = UspsZone::find($id);
		$zonelist = DB::table('zone_list')->orderBy('zone','asc')->get();
		return view('uspszone.edit',compact('zone','zonelist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
		
		// validation
        $request->validate([
			'zip_code' => 'required',
			'zone_name' => 'required',		
		]);
		
        $zone_details = DB::table('zone_list')->where('zone',$request->input('zone_name'))->get();
        if($zone_details->count() > 0)
            $zone_id = $zone_details[0]->id;
        else{//insert new zone in master
            $insertarray = array('zone'=>$request->input('zone_name'),'created_by'=>$uid,'updated_by'=>$uid);
            $zone = Zone::create($insertarray);
            $zone_id = $zone->id;
        }
 
		// update value in db
		$zone = UspsZone::find($id);			
        $zone->zone_id = $zone_id;
        $zone->zip_code = $request->input('zip_code');      
        $zone->updated_by = $uid;
        $zone->save();
		
		return redirect()->route('uspszone.index')
                        ->with('success','Zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 		// delete row
		DB::table("usps_zone")->where('id',$id)->delete();
        return redirect()->route('uspszone.index')
                        ->with('success','Zone deleted successfully.');
    }	
	
}