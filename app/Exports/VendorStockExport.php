<?php

namespace App\Exports;

use App\VendorStock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

//class StockExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class VendorStockExport implements FromView
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
			$query = VendorStock::select('vendor_stocks.*');
			if(!empty($vendor_name))
				$query->where('vendor_name', '=', $vendor_name);
			if(!empty($isbnno))
				$query->where('isbnno', '=', $isbnno);
			if(!empty($name))
				$query->where('name', '=', $name);
			if(!empty($author))
				$query->where('author', '=', $author);
			if(!empty($publisher))
				$query->where('publisher', '=', $publisher);
			if(!empty($binding_type))
				$query->where('binding_type', '=', $binding_type);
			if(!empty($stock_date))
				$query->where('stock_date', '=', $stock_date);
				
			$results = $query->orderBy('vendor_name', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('vendorstocks.exportstock', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
