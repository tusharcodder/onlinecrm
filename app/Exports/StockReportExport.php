<?php

namespace App\Exports;

use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class StockExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class StockReportExport implements FromView
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
		$sku_code = $formval->sku_code;
		
		$clscon = '';
		$salcon = '';
		if(!empty($from_date)){
			$clscon .= " AND clstock.stock_date >= '$from_date'";
			$salcon .= " AND sales.sale_date >= '$from_date'";
		}if(!empty($to_date)){
			$clscon .= " AND clstock.stock_date <= '$to_date'";
			$salcon .= " AND sales.sale_date <= '$to_date'";
		}
		
		$stockreports = DB::table('stocks')
		->join('product_images', 'product_images.product_code', '=', 'stocks.product_code')
		->select('stocks.brand','product_images.image_url as img_url','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.product_code','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code $clscon GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as sale_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code AND sales.sku_code = stocks.sku_code $salcon GROUP BY sales.product_code, sales.sku_code) as net_sale_qty"));
			
		if(!empty($lotno))
			$stockreports = $stockreports->where('stocks.lotno',$lotno);
		if(!empty($brand))
			$stockreports = $stockreports->where('stocks.brand',$brand);
		if(!empty($category))
			$stockreports = $stockreports->where('stocks.category',$category);
		if(!empty($gender))
			$stockreports = $stockreports->where('stocks.gender',$gender);
		if(!empty($colour))
			$stockreports = $stockreports->where('stocks.colour',$colour);
		if(!empty($product_code))
			$stockreports = $stockreports->where('stocks.product_code',$product_code);
		if(!empty($sku_code))
			$stockreports = $stockreports->where('stocks.sku_code',$sku_code);
		if(!empty($from_date))
			$stockreports = $stockreports->where('stocks.stock_date', '>=' ,$from_date);
		if(!empty($to_date))
			$stockreports = $stockreports->where('stocks.stock_date', '<=' ,$to_date);

		$stockreports = $stockreports->groupBy('stocks.product_code','stocks.sku_code');
		$stockreports = $stockreports->orderBy('stocks.stock_date','DESC');
		$results = $stockreports->get();
			
        return view('reports.exportstockreport', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}