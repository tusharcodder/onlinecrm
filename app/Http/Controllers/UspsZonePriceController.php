<?php

namespace App\Http\Controllers; 

use File;
use Session;
use App\UspsZonePrice;

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

class UspsZonePriceController extends Controller
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
		$zoneprice  = UspsZonePrice::select('*','zone_list.zone as zone_name')
        ->leftjoin('zone_list','zone_list.id','=','usps_zone_price_list.zone_id')
        ->where(function($query) use ($search) {
            $query->where('zone_list.zone','LIKE','%'.$search.'%')
               ->orWhere('usps_zone_price_list.lbs_wgt_to','LIKE','%'.$search.'%')
                ->orWhere('usps_zone_price_list.lbs_wgt_from','LIKE','%'.$search.'%');	
                
        })->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $zoneprice->appends ( array (
			'search' => $search
		));
		
        return view('uspszoneprice.index',compact('zoneprice','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zonelist = DB::table('zone_list')->get();
		return view('uspszoneprice.create',compact('zonelist'));
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
		
		// validation
        $request->validate([
			'wgt_lbs_from' => 'required',
            'wgt_lbs_to' => 'required',
			'zone_price' => 'required',
            'zonelist'  => 'required',		
		]);
		$zonelist = $request->input('zonelist');
        foreach($zonelist as $val) {
            // save value in db
            UspsZonePrice::create([
                'lbs_wgt_from' => $request->input('wgt_lbs_from'),
                'lbs_wgt_to' => $request->input('wgt_lbs_to'),
                'zone_price' => $request->input('zone_price'),	
                'zone_id'   =>	$val,		
                'created_by' => $uid,
                'updated_by' => $uid
            ]);
        }
		
		return redirect()->route('uspszoneprice.index')
                        ->with('success','Zone Price added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = UspsZonePrice::find($id);
		return view('uspszoneprice.show',compact('zone'));
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
        $zone = UspsZonePrice::find($id);
		$zonelist = DB::table('zone_list')->orderBy('zone','asc')->get();
		return view('uspszoneprice.edit',compact('zone','zonelist'));
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
       
        $user = Auth::user();
		$uid = $user->id;
		
		// validation
        $request->validate([
			'wgt_lbs_from' => 'required',
            'wgt_lbs_to' => 'required',
			'zone_price' => 'required',
            'zonelist'  => 'required',		
		]);
		
		// update value in db
		$zone = UspsZonePrice::find($id);			
        $zone->lbs_wgt_from = $request->input('wgt_lbs_from');
        $zone->lbs_wgt_to = $request->input('wgt_lbs_from');
        $zone->zone_price = $request->input('zone_price');
        $zone->zone_id = $request->input('zonelist');      
        $zone->updated_by = $uid;
        $zone->save();
		
		return redirect()->route('uspszoneprice.index')
                        ->with('success','Zone Price updated successfully.');
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
		DB::table("usps_zone_price_list")->where('id',$id)->delete();
        return redirect()->route('uspszoneprice.index')
                        ->with('success','Zone Price deleted successfully.');
    }	
	
}