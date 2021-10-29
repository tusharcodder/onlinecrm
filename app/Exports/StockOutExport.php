<?php

namespace App\Exports;

use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class stockOutExport implements FromView
{	
	/**
    * get request values
    */
	public function __construct($request, $thresholdval)
    {
		// get form request value
		$this->request = $request;
		$this->thresholdval = empty($thresholdval) ? 0 : $thresholdval['out_of_stock_threshold'];
    }
	
	/**
    * get values from view
    */
	public function view(): View
    {	
		//
		$stockout = DB::table('stocks')
		->join('product_images', 'product_images.product_code', '=', 'stocks.product_code')
		->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.product_code','stocks.stock_date','stocks.size','stocks.description','product_images.image_url as img_url', DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code and sales.sku_code = stocks.sku_code GROUP BY sales.product_code, sales.sku_code) as sale_qty"));
		$stockout = $stockout->groupBy('stocks.product_code', 'stocks.sku_code');
		$stockout = $stockout->orderBy('stocks.product_code','ASC');
		$stockout = $stockout->orderBy('stocks.sku_code','ASC');
		$stockout = $stockout->havingRaw("sum(stocks.quantity) - (SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code and sales.sku_code = stocks.sku_code GROUP BY sales.product_code, sales.sku_code) <= $this->thresholdval");
		$results = $stockout->get();
        return view('stocks.outstock', [
			'results' => $results
		]);
    }
}