<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\StockReminder;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('permission:show-dashboard', ['only' => ['index']]);
        //$this->middleware(['auth','verified']);
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		// get low stock and out of stock item count
		$thresholdval = StockReminder::first();
		$thresholdval = (!empty($thresholdval) ? $thresholdval->getAttributes() : $thresholdval);
		$lowval = empty($thresholdval) ? 0 : $thresholdval['low_stock_threshold'];
		$outval =empty($thresholdval) ? 0 : $thresholdval['out_of_stock_threshold'];
		
		//low stock count
		$stocklow = DB::table('stocks')
		->select(DB::raw('count(sku_code) as totoutstk'));
		$stocklow = $stocklow->groupBy('stocks.product_code', 'stocks.sku_code');
		$stocklow = $stocklow->havingRaw("sum(stocks.quantity) - (SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code and sales.sku_code = stocks.sku_code GROUP BY sales.product_code, sales.sku_code) <= $lowval");
		$resultlows = $stocklow->get();
		$resultlows = empty($resultlows) ? 0 : count($resultlows);
		
		//out of tock count
		$stockout = DB::table('stocks')->select(DB::raw('count(sku_code) as totoutstk'));
		$stockout = $stockout->groupBy('stocks.product_code', 'stocks.sku_code');
		$stockout = $stockout->havingRaw("sum(stocks.quantity) - (SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code and sales.sku_code = stocks.sku_code GROUP BY sales.product_code, sales.sku_code) <= $outval");
		$resultouts = $stockout->get();
		$resultouts = empty($resultouts) ? 0 : count($resultouts);
		
        return view('home',compact('resultouts','resultlows'));
    }
	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function saleChartDetails()
    {
		// query for get sale chart details month wise of last 12 month
		$salecharts = Sale::
					select(DB::raw('sum(quantity) as totalqty'), DB::raw('MONTH(sale_date) as month'), DB::raw('MONTHNAME(sale_date) as monthname'), DB::raw('Year(sale_date) as year'),
					DB::raw("DATE_FORMAT(sale_date, '%b-%Y') as monyear"))
					->where('sale_date', '<=', DB::raw('NOW()'))
					->where('sale_date', '>=', DB::raw('Date_add(Now(),interval - 12 month)'))
					->groupBy('month','monyear')
					->orderBy('year')
					->orderBy('month')->get();
		
		return json_encode($salecharts);
    }
}
