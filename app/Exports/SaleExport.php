<?php

namespace App\Exports;

use App\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

//class SaleExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class SaleExport implements FromView
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
		$vendor_type = $this->request['vendor_type'];
		$vendor_name = $this->request['vendor_name'];
		$aggregator_vendor_name = $this->request['aggregator_vendor_name'];
		$brand = $this->request['brand'];
		$category = $this->request['category'];
		$invoice_no = $this->request['invoice_no'];
		$colour = $this->request['colour'];
		$product_code = $this->request['product_code'];
		$from_date = $this->request['from_date'];
		$to_date = $this->request['to_date'];
		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		if($format == "withheading"){ // data with heading
			// get details of sales from query
			$query = Sale::select('*');
			if(!empty($vendor_type))
				$query->where('vendor_type', '=', $vendor_type);
			if(!empty($vendor_name))
				$query->where('vendor_name', '=', $vendor_name);	
			if(!empty($aggregator_vendor_name))
				$query->where('aggregator_vendor_name', '=', $aggregator_vendor_name);
			if(!empty($brand))
				$query->where('brand', '=', $brand);
			if(!empty($category))
				$query->where('category', '=', $category);
			if(!empty($invoice_no))
				$query->where('invoice_no', '=', $invoice_no);
			if(!empty($colour))
				$query->where('colour', '=', $colour);
			if(!empty($product_code))
				$query->where('product_code', '=', $product_code);
			if(!empty($from_date))
				$query->where('sale_date', '>=', $from_date);
			if(!empty($to_date))
				$query->where('sale_date', '<=', $to_date);
				
			$results = $query->orderBy('sale_date', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('sales.exportsale', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}