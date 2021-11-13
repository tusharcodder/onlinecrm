<?php

namespace App\Exports;

use App\CustomerOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class CustomerOrderExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class CustomerOrderExport implements FromView
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
		$purchase_date = $this->request['purchase_date'];
		$payments_date = $this->request['payments_date'];
		$reporting_date = $this->request['reporting_date'];
		$promise_date = $this->request['promise_date'];
		$buyer_name = $this->request['buyer_name'];
		$buyer_phone_number = $this->request['buyer_phone_number'];
		$product_name = $this->request['product_name'];
		$sku = $this->request['sku'];
		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = CustomerOrder::select('*');
			if(!empty($order_id))
				$query->where('order_id', '=', $order_id);
			if(!empty($order_item_id))
				$query->where('order_item_id', '=', $order_item_id);
			if(!empty($buyer_name))
				$query->where('buyer_name', '=', $buyer_name);
			if(!empty($buyer_phone_number))
				$query->where('buyer_phone_number', '=', $buyer_phone_number);
			if(!empty($product_name))
				$query->where('product_name', '=', $product_name);
			if(!empty($sku))
				$query->where('sku', '=', $sku);
			if(!empty($purchase_date))
				$query->where(DB::raw("(DATE_FORMAT(purchase_date,'%Y-%m-%d'))"), '=', $purchase_date);
			if(!empty($payments_date))
				$query->where(DB::raw("(DATE_FORMAT(payments_date,'%Y-%m-%d'))"), '=', $payments_date);
			if(!empty($reporting_date))
				$query->where(DB::raw("(DATE_FORMAT(reporting_date,'%Y-%m-%d'))"), '=', $reporting_date);
			if(!empty($promise_date))
				$query->where(DB::raw("(DATE_FORMAT(promise_date,'%Y-%m-%d'))"), '=', $promise_date);
				
			$results = $query->orderBy('id', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('customerorders.exportorders', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
