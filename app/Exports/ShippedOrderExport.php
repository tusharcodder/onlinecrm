<?php

namespace App\Exports;

use App\OrderTrack;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class CustomerOrderExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class ShippedOrderExport implements FromView
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
		$order_id = $this->request['order_id'];
		$order_item_id = $this->request['order_item_id'];
		$from_date = $this->request['from_date'];
		$to_date = $this->request['to_date'];
		
		$warehousename = $this->request['warehouse_name'];
		$sku = $this->request['sku'];		
		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		/* $reporting_date = $this->request['reporting_date'];
		$promise_date = $this->request['promise_date'];
		$buyer_name = $this->request['buyer_name'];
		$buyer_phone_number = $this->request['buyer_phone_number']; */
		
		
		
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = OrderTrack::select('order_tracking.*','order_tracking.warehouse_name as wname','order_tracking.warehouse_id as wid','order_tracking.warehouse_name as wname','customer_orders.*','suppliers.name as shipper_name','market_places.name as markname','skudetails.isbn13 as isbnno','skudetails.pkg_wght as pkg_wght','skudetails.wght as wght','skudetails.oz_wt as oz_wt','book_details.name as proname', 'book_details.author as author', 'book_details.publisher as publisher')
					->leftJoin("skudetails","skudetails.sku_code","=","order_tracking.sku")
					->leftJoin("market_places","market_places.id","=","skudetails.market_id")
					->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
					->leftJoin("customer_orders","customer_orders.order_id","=","order_tracking.order_id")
					->leftJoin("suppliers","suppliers.id","=","order_tracking.shipper_id");
			if(!empty($order_id))
				$query->where('order_tracking.order_id', '=', $order_id);
			if(!empty($order_item_id))
				$query->where('order_tracking.order_item_id', '=', $order_item_id);			
			if(!empty($warehousename))
				$query->where('order_tracking.warehouse_name', '=', $warehousename);
			if(!empty($sku))
				$query->where('order_tracking.sku', '=', $sku);
			if(!empty($from_date))
				$query->where(DB::raw("(DATE_FORMAT(order_tracking.shipment_date,'%Y-%m-%d'))"), '>=', $from_date);
			if(!empty($to_date))
				$query->where(DB::raw("(DATE_FORMAT(order_tracking.shipment_date,'%Y-%m-%d'))"), '<=', $to_date);		
				
			$results = $query->orderBy('order_tracking.id', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('reports.exportshippedcustomerorder', [
			'results' => $results,
			'exporttype' => $exporttype, 
		]);
    }
}
