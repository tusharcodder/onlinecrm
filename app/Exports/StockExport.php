<?php

namespace App\Exports;

use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

//class StockExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class StockExport implements FromView
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
		$manufacturer_name = $this->request['manufacturer_name'];
		$brand = $this->request['brand'];
		$category = $this->request['category'];
		$gender = $this->request['gender'];
		$colour = $this->request['colour'];
		$product_code = $this->request['product_code'];
		$from_date = $this->request['from_date'];
		$to_date = $this->request['to_date'];
		$format = $this->request['format'];
		$exporttype = $this->request['exporttype'];
		
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = Stock::select('stocks.*','product_images.image_url as img_url')
			->join('product_images', 'product_images.product_code', '=', 'stocks.product_code');
			if(!empty($manufacturer_name))
				$query->where('stocks.manufacturer_name', '=', $manufacturer_name);
			if(!empty($brand))
				$query->where('stocks.brand', '=', $brand);
			if(!empty($category))
				$query->where('stocks.category', '=', $category);
			if(!empty($gender))
				$query->where('stocks.gender', '=', $gender);
			if(!empty($colour))
				$query->where('stocks.colour', '=', $colour);
			if(!empty($product_code))
				$query->where('stocks.product_code', '=', $product_code);
			if(!empty($from_date))
				$query->where('stocks.stock_date', '>=', $from_date);
			if(!empty($to_date))
				$query->where('stocks.stock_date', '<=', $to_date);
				
			$results = $query->orderBy('stocks.stock_date', 'ASC')->get();
		}else // only data heading for format
			$results = collect([]);
			
        return view('stocks.exportstock', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
