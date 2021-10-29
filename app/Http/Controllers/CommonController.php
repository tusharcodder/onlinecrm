<?php

namespace App\Http\Controllers;

use App\Common;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;

class CommonController extends Controller{
	//
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVendor($val)
    {
        $vendors = Vendor::where('type',$val)->pluck('vendor_name','id')->all();
        return json_encode($vendors);
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAggregatorVendor($val,$type)
    {
        $aggregatordetails = Vendor::select('aggregator_has_vendors.*')->join("aggregator_has_vendors","aggregator_has_vendors.vendor_id","=","vendors.id")
            ->where("vendors.vendor_name",$val)
            ->where("vendors.type",$type)
            ->get();
        return json_encode($aggregatordetails);
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete($table, $column, Request $request)
    {
		$data = DB::table($table)->select($column)
                ->where($column,"LIKE","%{$request->search}%")
                ->groupBy($column)
                ->get();
		
		$response = array();
		foreach($data as $autocomplate){
			$response[] = array("value"=>$autocomplate->$column,"label"=>$autocomplate->$column);
		}
	  
        return response()->json($response);
	}
}
?>