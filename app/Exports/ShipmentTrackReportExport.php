<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class ShipmentTrackReportExport implements FromView
{	
	/**
    * get request values
    */
	public function __construct($request)
    {
		// get form request value
		$this->request = $request;
    }
	
	/**
    * get values from view
    */
	public function view(): View
    {
		$exporttype = $this->request['exporttype'];
		$order_item_id = $this->request['order_item_id'];
		$order_id = $this->request['order_id'];
		$from_date = $this->request['from_date'];
		$to_date = $this->request['to_date'];
		$trackid = $this->request['trackid'];
		
		$results = DB::table('order_tracking')
			->select('order_tracking.*','customer_orders.carrier_service as carrier_service','customer_orders.carrier_name as carrier_name');
		$results = $results->join("customer_orders",function($join){
			$join->on("customer_orders.order_id","=","order_tracking.order_id")
				 ->on("customer_orders.order_item_id","=","order_tracking.order_item_id");
			});
			
			if(!empty($order_id))
				$results->where('order_tracking.order_id', '=', $order_id);
			if(!empty($order_item_id))
				$results->where('order_tracking.order_item_id', '=', $order_item_id);
			if(!empty($trackid))
				$results->where('order_tracking.shipper_tracking_id', '=', $trackid);
			if(!empty($from_date))
				$results->where(DB::raw("(DATE_FORMAT(order_tracking.shipment_date,'%Y-%m-%d'))"), '>=', $from_date);
			if(!empty($to_date))
				$results->where(DB::raw("(DATE_FORMAT(order_tracking.shipment_date,'%Y-%m-%d'))"), '<=', $to_date);	

			
			$results = $results->orderBy('order_tracking.shipment_date','asc')
			->get();
		

		return view('reports.exportshipmenttrackreports', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }	
}