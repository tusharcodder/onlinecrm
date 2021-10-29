<?php

namespace App\Exports;

use App\PerformanceReport;
use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use App\Support\Collection;

//class StockExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class PerformanceReportExport implements FromView
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
		$lotno = $formval->lotno;
		$brand = $formval->brand;
		$category = $formval->category;
		$gender = $formval->gender;
		$colour = $formval->colour;
		$product_code = $formval->product_code;
		$from_date = $formval->from_date;
		$to_date = $formval->to_date;
		$percat = $formval->percategory;
		$sku_code = $formval->sku_code;
		
		$perarr = [];
		$stkarr = [];
		
		$clscon = '';
		$salcon = '';
		if(!empty($from_date)){
			$clscon .= " AND clstock.stock_date >= '$from_date'";
			$salcon .= " AND sales.sale_date >= '$from_date'";
		}if(!empty($to_date)){
			$clscon .= " AND clstock.stock_date <= '$to_date'";
			$salcon .= " AND sales.sale_date <= '$to_date'";
		}
		
		// get performance master date
		/* $performancesdata = DB::table('performances')
							->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code $clscon GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as sale_qty"),'performances.category as percat','performances.product_code','performances.sale_through', DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code AND sales.sku_code = stocks.sku_code $salcon GROUP BY sales.product_code, sales.sku_code) as net_sale_qty"))
							->join("stocks","stocks.product_code","=","performances.product_code","INNER"); */
							
		$performancesdata = DB::table('performances')
							->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','product_images.image_url as img_url','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code $clscon GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as sale_qty"),'performances.category as percat','performances.product_code','performances.sale_through', DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as net_sale_qty"))
							->join("stocks","stocks.product_code","=","performances.product_code","INNER")
							->join('product_images', 'product_images.product_code', '=', 'stocks.product_code');
							
		if(!empty($lotno))
			$performancesdata = $performancesdata->where('stocks.lotno',$lotno);
		if(!empty($brand))
			$performancesdata = $performancesdata->where('stocks.brand',$brand);
		if(!empty($category))
			$performancesdata = $performancesdata->where('stocks.category',$category);
		if(!empty($gender))
			$performancesdata = $performancesdata->where('stocks.gender',$gender);
		if(!empty($colour))
			$performancesdata = $performancesdata->where('stocks.colour',$colour);
		if(!empty($product_code))
			$performancesdata = $performancesdata->where('performances.product_code',$product_code);
		if(!empty($sku_code))
			$performancesdata = $performancesdata->where('stocks.sku_code',$sku_code);
		if(!empty($from_date))
			$performancesdata = $performancesdata->where('stocks.stock_date', '>=' ,$from_date);
		if(!empty($to_date))
			$performancesdata = $performancesdata->where('stocks.stock_date', '<=' ,$to_date);
		
		//$performancesdata = $performancesdata->groupBy('performances.product_code','stocks.sku_code','performances.category');
		$performancesdata = $performancesdata->groupBy('performances.product_code','performances.category');
		$performancesdata = $performancesdata->orderBy('performances.product_code','DESC');
		$performancesdata = $performancesdata->get();
		
		if(!empty($performancesdata->count())){
			foreach($performancesdata as $key => $val){
				//$perarr[$val->sku_code][$val->percat] = $val->sale_through;
				$perarr[$val->product_code][$val->percat] = $val->sale_through;
				$stkarr[$val->product_code] = $val;
			}
		}
		
		if(!empty($perarr)){
			$items = [];
			foreach($stkarr as $key => $val){
				
				// get sale through of stock data
				$val->quantity = empty($val->quantity) ? 0 : $val->quantity;
				$val->closing_qty = empty($val->closing_qty) ? 0 : $val->closing_qty;
				$val->sale_qty = empty($val->sale_qty) ? 0 : $val->sale_qty;
				
				$salethrough = 0;
				if($val->closing_qty > 0 ){
					$salethrough = ($val->sale_qty * 100)/$val->closing_qty;
					$salethrough = number_format((float)$salethrough, 2, '.', '');
				}
				
				// check item is fast medium and slow based on salethrough condition
				if(isset($perarr[$key]['Fast']) && $salethrough >= $perarr[$key]['Fast']){ // for fast (>= sale through)
					$val->performance = 'Fast';
				}elseif(isset($perarr[$key]['Slow']) && $salethrough <= $perarr[$key]['Slow']){  // for slow (<= sale through)
					$val->performance = 'Slow';	
				}else{ // for medium (not exists in both condition)
					$val->performance = 'Medium';
				}
				$items[] =  $val;
			}
			
			// get data based on category performance
			$perdata = new Collection($items);
			if(!empty($percat))
				$perdata = $perdata->where('performance' ,$percat);
			
			// create item array accodring to master performance details
			$results = $perdata->sortBy('performance');
		}else{
			$results = new Collection([]);
		}
			
        return view('reports.exportperformancereport', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}