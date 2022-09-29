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
	
	/**
     * create cron for get the response of track order
     *
     * @return \Illuminate\Http\Response
     */
    public function shipmentTrackStatus(Request $request)
    {
		$data = DB::table('order_tracking')->select("*")
				->where(function($query){
					$query->where('tracking_status',"!=","delivered");
					$query->where('tracking_status',"!=","failed");
					$query->orWhereNull('tracking_status');
				 })                
                ->groupBy('shipper_tracking_id')
                ->get();
		if(!empty(count($data))){
			foreach($data as $val){
				$track_numbers = array("references" => ["$val->shipper_tracking_id"]);
				//$track_numbers = explode(',',$track_numbers);
				
				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://api.ypn.io/v2/shipping/track',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>json_encode($track_numbers),
				  CURLOPT_HTTPHEADER => array(
					'Authorization: Basic TlRRPS4rbHNORytJdVRpMzZWOHpjT0JFLzd2N1Axc3luWFh5c0VKL3pTaE41M3ZjPTo=',
					'Content-Type: application/json'
				  ),
				));

				$response = curl_exec($curl);
				$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				curl_close($curl);
				$responsedata = json_decode($response);
				if($http_status == 200){
					$track_status = $responsedata->trackers[0]->status ?? '';
					if(!empty($track_status)){
						// save this value on orderid and order item id
						DB::table('order_tracking')
						->where('shipper_tracking_id', $val->shipper_tracking_id)
						->update([
							'tracking_status' => $track_status->status,
							'tracking_api_response' => $response,
							'api_response_code' => $http_status,
						]);
					}
				}else{
					// save this value on orderid and order item id
					DB::table('order_tracking')
					->where('shipper_tracking_id', $val->shipper_tracking_id)
					->update([
						'tracking_status' => 'failed',
						'tracking_api_response' => $response,
						'api_response_code' => $http_status,
					]);
				}
				
				// sleep for 10 second
				sleep(10);
			}
		}
        return true;
	} 
}
?>