<?php

namespace App\Exports;

use App\Discount;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class StockExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class DiscountReportExport implements FromView
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
		$exporttype = $this->request['exporttype'];
		$formval = $this->request['formval'];
		$formval = json_decode($formval);
		$type = $formval->type;
		$vendor = $formval->vendor;
		$aggregator_vendor = $formval->aggregator_vendor;
		$lotno = $formval->lotno;
		$brand = $formval->brand;
		$category = $formval->category;
		$gender = $formval->gender;
		$colour = $formval->colour;
		$product_code = $formval->product_code;
		$from_date = $formval->from_date;
		$to_date = $formval->to_date;
		
		$discountreports = DB::table('discounts')
			->select('discounts.*','stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','product_images.image_url as img_url','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.vendor_type = discounts.vendor_type and sales.vendor_name = discounts.vendor_name and (sales.aggregator_vendor_name = discounts.aggregator_vendor_name or sales.aggregator_vendor_name is null) and sales.product_code = discounts.product_code GROUP BY sales.vendor_type,sales.vendor_name,sales.aggregator_vendor_name,sales.product_code) as sale_qty"),'stocks.image_url')
			->join("stocks","stocks.product_code","=","discounts.product_code","Inner")
			->join('product_images', 'product_images.product_code', '=', 'stocks.product_code');
		
		if(!empty($type))
			$discountreports = 	$discountreports->where('discounts.vendor_type',$type);
		if(!empty($vendor))
			$discountreports = 	$discountreports->where('discounts.vendor_name',$vendor);
		if(!empty($aggregator_vendor))
			$discountreports = 	$discountreports->where('discounts.aggregator_vendor_name',$aggregator_vendor);
		if(!empty($lotno))
			$discountreports = 	$discountreports->where('stocks.lotno',$lotno);
		if(!empty($brand))
			$discountreports = 	$discountreports->where('stocks.brand',$brand);
		if(!empty($category))
			$discountreports = 	$discountreports->where('stocks.category',$category);
		if(!empty($gender))
			$discountreports = 	$discountreports->where('stocks.gender',$gender);
		if(!empty($colour))
			$discountreports = 	$discountreports->where('stocks.colour',$colour);
		if(!empty($product_code))
			$discountreports = 	$discountreports->where('discounts.product_code',$product_code);
		if(!empty($from_date))
			$discountreports = 	$discountreports->where('discounts.valid_from_date', '>=' ,$from_date);
		if(!empty($to_date))
			$discountreports = 	$discountreports->where('discounts.valid_from_date', '<=' ,$to_date);
			
		$discountreports = 	$discountreports->groupBy('discounts.vendor_type','discounts.vendor_name','discounts.aggregator_vendor_name','discounts.product_code','discounts.valid_from_date');
		$discountreports = 	$discountreports->orderBy('discounts.valid_from_date','DESC');
		$discountreports = 	$discountreports->orderBy('stocks.id','DESC');
		$results = $discountreports->get();
			
        return view('reports.exportdiscountreport', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}