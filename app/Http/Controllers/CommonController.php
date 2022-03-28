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
    public function autocomplete($table, $column, Request $request)
    {
		$data = DB::table($table)->select($column)
                ->where($column,"LIKE","%{$request->search}%")
                ->groupBy($column)
                ->get();
		
		$response = array();
		foreach($data as $autocomplate){
			$response[] = array(
				"value"=>$autocomplate->$column,
				"label"=>$autocomplate->$column
			);
		}
	  
        return response()->json($response);
	}   
      public function getIsbn(Request $request)
      {
        $search = $request['search'];
        $isbn13 = DB::table('skudetails')->select(DB::raw("DISTINCT isbn13"));
        if($search)
        {
                $isbn13 = $isbn13->where('skudetails.isbn13', 'LIKE', '%'.$search .'%');
        }
        $isbn13 = $isbn13->get();

        return $isbn13;

      }  
}
?>