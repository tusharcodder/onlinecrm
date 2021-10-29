<?php

namespace App\Exports;

use App\Performance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerformanceExport implements FromCollection, WithHeadings, WithMapping
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
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
		$product_code = $this->request['product_code'];
		$category = $this->request['category'];
		$sale_through = $this->request['salethrough'];
		
		$format = $this->request['format'];
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = Performance::select('*');
			if(!empty($product_code))
				$query->where('product_code', '=', $product_code);
			if(!empty($category))
				$query->where('category', '=', $category);
			if(!empty($sale_through))
				$query->where('sale_through', '=', $sale_through);

			$results = $query->orderBy('sale_through', 'ASC')->get();
			return $results;
		}else // only data heading for format
			return collect([]);
    }
	
	public function headings(): array
    {
        return [
            'product_code',
            'category',
            'sale_through',
        ];
    }
	
	 public function map($Vendors): array
    {
        return [
			$Vendors->product_code,
            $Vendors->category,
            $Vendors->sale_through,
            //Carbon::parse($Vendors->valid_from_date)->format('d-m-Y'),
            //Carbon::parse($Vendors->valid_from_date)->format('d-m-Y'),
        ];
    }
}
