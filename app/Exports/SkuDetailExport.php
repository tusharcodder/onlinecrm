<?php

namespace App\Exports;

use App\SkuDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

//class SkuCodeExport implements FromCollection
class SkuDetailExport  implements FromView
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
		$market_place = $this->request['market_place'];
		$warehouse = $this->request['warehouse'];
		$isbn13 = $this->request['isbn13'];
		$isbn10 = $this->request['isbn10'];
		$skucode = $this->request['skucode'];
		$mrp = $this->request['mrp'];
		$weight = $this->request['weight'];
		$pkg_weight = $this->request['pkg_weight'];
	
		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = SkuDetail::select('skudetails.*','market_places.name as Market_Place','warehouses.name as Warehouse')
			->join("market_places","market_places.id","=","skudetails.market_id")
			->join("warehouses","warehouses.id","=","skudetails.warehouse_id");			
			if(!empty($market_place))
				$query->where('skudetails.market_id', '=', $market_place);
			if(!empty($warehouse))
				$query->where('skudetails.warehouse_id', '=', $warehouse);
			if(!empty($isbn13))
				$query->where('skudetails.isbn13', '=', $isbn13);
			if(!empty($isbn10))
				$query->where('skudetails.isbn10', '=', $isbn10);
			if(!empty($skucode))
				$query->where('skudetails.skucode', '=', $skucode);
			if(!empty($mrp))
			$query->where('skudetails.mrp', '=', $mrp);			
			if(!empty($weight))
			$query->where('skudetails.wght', '=', $weight);		
			if(!empty($pkg_weight))
			$query->where('skudetails.pkg_wght', '=', $pkg_weight);		

			$results = $query->orderBy('skudetails.market_id', 'ASC')
            ->orderBy('skudetails.warehouse_id', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('skudetails.skucode-export', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
