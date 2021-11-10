<?php

namespace App\Exports;

use App\CustomerOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

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
		$stock_date = $this->request['stock_date'];
		$vendor_name = $this->request['vendor_name'];
		$isbnno = $this->request['isbnno'];
		$name = $this->request['name'];
		$author = $this->request['author'];
		$publisher = $this->request['publisher'];
		$binding_type = $this->request['binding_type'];

		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = VendorStock::select('vendor_stocks.*','bindings.name as binding_type','currenciess.name as currency','vendors.name as vendor_name')
			->join("bindings","bindings.id","=","vendor_stocks.binding_id")
			->join("currenciess","currenciess.id","=","vendor_stocks.currency_id")
			->join("vendors","vendors.id","=","vendor_stocks.vendor_id");
			if(!empty($vendor_name))
				$query->where('vendor_stocks.vendor_id', '=', $vendor_name);
			if(!empty($isbnno))
				$query->where('vendor_stocks.isbnno', '=', $isbnno);
			if(!empty($name))
				$query->where('vendor_stocks.name', '=', $name);
			if(!empty($author))
				$query->where('vendor_stocks.author', '=', $author);
			if(!empty($publisher))
				$query->where('vendor_stocks.publisher', '=', $publisher);
			if(!empty($binding_type))
				$query->where('vendor_stocks.binding_id', '=', $binding_type);
			if(!empty($stock_date))
				$query->where('vendor_stocks.stock_date', '=', $stock_date);
			if(!empty($currency))
				$query->where('vendor_stocks.currency_id', '=', $currency);
				
			$results = $query->orderBy('vendor_stocks.vendor_id', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('vendorstocks.exportstock', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
