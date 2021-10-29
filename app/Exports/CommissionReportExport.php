<?php

namespace App\Exports;

use App\CommissionReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class StockExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class CommissionReportExport implements FromView
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
		$skucode = $formval->skucode;
		$brand = $formval->brand;
		$category = $formval->category;	
		$product_code = $formval->product_code;
		$from_date = $formval->from_date;
		$to_date = $formval->to_date;
		$commissiontype = $formval->ctype;	
		
		$commissionreports = DB::table('sales as s')
			->select('s.sale_date','s.vendor_type as Type','s.vendor_name as Venname','s.aggregator_vendor_name as avname','s.Brand','s.category', 's.Product_code as pcode','s.sku_code as SkuCode',DB::raw('sum(s.total_sale_amount) as saleamt'),'vendors.commission',DB::raw('CAST( (sum(s.total_sale_amount))*vendors.commission/100 AS DECIMAL(10,2)) as commvalue'),
			DB::raw('sum(case when igst is null then (cgst+sgst) else igst end) as gst'))->join("vendors","vendors.vendor_name","=","s.vendor_name","Inner");
		if(!empty($type))
			$commissionreports = $commissionreports->where('s.vendor_type',$type);
		if(!empty($vendor))
			$commissionreports = $commissionreports->where('s.vendor_name',$vendor);
		if(!empty($aggregator_vendor))
			$commissionreports = $commissionreports->where('s.aggregator_vendor_name',$aggregator_vendor);
		if(!empty($skucode))
			$commissionreports = $commissionreports->where('s.sku_code',$skucode);
		if(!empty($brand))
			$commissionreports = $commissionreports->where('s.brand',$brand);
		if(!empty($category))
			$commissionreports = $commissionreports->where('s.category',$category);
		
		if(!empty($product_code))
			$commissionreports = $commissionreports->where('s.product_code',$product_code);
		if(!empty($from_date))
			$commissionreports = $commissionreports->where('s.sale_date', '>=' ,$from_date);
		if(!empty($to_date))
			$commissionreports = $commissionreports->where('s.sale_date', '<=' ,$to_date);
		$commissionreports = $commissionreports->where('s.vendor_type','!=','Aggregator');		
		$commissionreports = $commissionreports->groupBy('s.sku_code','s.product_code','s.sale_date');
		$results = $commissionreports->get();
		
        return view('reports.exportcommissionreport', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
	