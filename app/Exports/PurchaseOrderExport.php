<?php

namespace App\Exports;

use App\PurchaseOrder;
use App\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class PurchaseOrderExport implements FromCollection
class PurchaseOrderExport implements FromView
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
		$bill_no = $this->request['bill_no'];
		$isbn13 = $this->request['isbn13'];
		$vendor = $this->request['vendor'];
		$purchase_date_from = $this->request['purchase_date_from'];
		$purchase_date_to = $this->request['purchase_date_to'];		
		$quantity = $this->request['quantity'];
		$mrp = $this->request['mrp'];
		$discount = $this->request['discount'];
		$purchase_by = $this->request['purchase_by'];	
		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = PurchaseOrder::select('purchase_orders.*','book_details.name','vendors.name as vendor')
			->join('vendors','vendors.id','=','purchase_orders.vendor_id')
			->leftJoin('book_details','book_details.isbnno','=','purchase_orders.isbn13');
			if(!empty($bill_no))
				$query->where('bill_no', '=', $bill_no);
			if(!empty($isbn13))
				$query->where('isbn13', '=', $isbn13);
			if(!empty($vendor))
				$query->where('vendor_id', '=', $vendor);
			if(!empty($quantity))
				$query->where('quantity', '=', $quantity);
			if(!empty($mrp))
				$query->where('mrp', '=', $mrp);
			if(!empty($discount))
				$query->where('discount', '=', $discount);
			if(!empty($purchase_date_from))
				$query->where('purchase_date', '>=', $purchase_date_from);
			if(!empty($purchase_date_to))
				$query->where('purchase_date', '<=', $purchase_date_to);
            if(!empty($purchase_by))
				$query->where('purchase_by', '=', $purchase_by);    	
				
			$results = $query->orderBy('id', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('purchaseorders.exportpurchase', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
