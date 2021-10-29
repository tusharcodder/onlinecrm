<?php

namespace App\Exports;

use App\Discount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DiscountExport implements FromCollection, WithHeadings, WithMapping
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
		$type = $this->request['type'];
		$vendor = $this->request['vendor'];
		$aggregator_vendor = $this->request['aggregator_vendor'];
		$product_code = $this->request['product_code'];
		$discount = $this->request['discount'];
		$from_date = $this->request['from_date'];
		$to_date = $this->request['to_date'];
		
		$format = $this->request['format'];
		if($format == "withheading"){ // data with heading
			// get details of discounts from query
			$query = Discount::select('*');
			if(!empty($type))
				$query->where('vendor_type', '=', $type);
			if(!empty($vendor))
				$query->where('vendor_name', '=', $vendor);
			if(!empty($aggregator_vendor))
				$query->where('aggregator_vendor_name', '=', $aggregator_vendor);
			if(!empty($product_code))
				$query->where('product_code', '=', $product_code);
			if(!empty($discount))
				$query->where('discount', '=', $discount);
			if(!empty($from_date))
				$query->where('valid_from_date', '>=', $from_date);
			if(!empty($to_date))
				$query->where('valid_from_date', '<=', $to_date);

			$results = $query->orderBy('vendor_type', 'ASC')->get();
			return $results;
		}else // only data heading for format
			return collect([]);
    }
	
	public function headings(): array
    {
        return [
            'type',
            'vendor_name',
            'aggregator_vendor_name',
            'product_code',
            'discount',
            'from_date',
            'to_date',
        ];
    }
	
	 public function map($Vendors): array
    {
        return [
            $Vendors->vendor_type,
            $Vendors->vendor_name,
			$Vendors->aggregator_vendor_name,
			$Vendors->product_code,
            $Vendors->discount,
            Carbon::parse($Vendors->valid_from_date)->format('d-m-Y h:i A'),
            Carbon::parse($Vendors->valid_from_date)->format('d-m-Y h:i A'),
        ];
    }
}
