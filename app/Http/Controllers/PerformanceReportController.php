<?php

namespace App\Http\Controllers;

use App\PerformanceReport;
use Illuminate\Http\Request;
use App\Stock;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerformanceReportExport;
use App\Support\Collection;

class PerformanceReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:performancereport', ['only' => ['index', 'search']]);
		$this->middleware('permission:downloadperformancereport', ['only' => ['downloadperformancereport']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		//
		$lotno = '';
		$brand = '';
		$category = '';
		$gender = '';
		$colour = '';
		$product_code = '';
		$from_date = '';
		$to_date = '';
		$percat = '';
		$sku_code = '';
		
		$perarr = [];
		$stkarr = [];
		
		$cat = ['Fast', 'Medium', 'Slow'];
		
		// get performance master date
		$performancesdata = DB::table('performances')
							->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code GROUP BY sales.product_code) as sale_qty"),'performances.category as percat','performances.product_code','performances.sale_through', DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code GROUP BY sales.product_code) as net_sale_qty"))
							->join("stocks","stocks.product_code","=","performances.product_code","INNER")
							->groupBy('performances.product_code','performances.category')
							->orderBy('performances.product_code','DESC')
							->get();
		
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
			
			// create item array accodring to master performance details
			$stockreports = (new Collection($items))->sortBy('performance')->paginate(10)->setPath('');
			
			return view('reports.performancereport',compact('stockreports', 'request', 'colour', 'brand', 'category', 'gender', 'lotno', 'product_code', 'from_date', 'to_date', 'percat', 'cat','sku_code'))
				->with('i', ($request->input('page', 1) - 1) * 10);
		}else{
			$items = [];
			$stockreports = (new Collection($items))->paginate(10)->setPath('');
			
			return view('reports.performancereport',compact('stockreports', 'request', 'colour', 'brand', 'category', 'gender', 'lotno', 'product_code', 'from_date', 'to_date', 'percat', 'cat','sku_code'))
				->with('i', ($request->input('page', 1) - 1) * 10);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
	
	 /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
		//
		$lotno = $request->input('lotno');
		$brand = $request->input('brand');
		$category = $request->input('category');
		$gender = $request->input('gender');
		$colour = $request->input('colour');
		$product_code = $request->input('product_code');
		$from_date = $request->input('from_date');
		$to_date = $request->input('to_date');
		$percat = $request->input('percategory');
		$sku_code = $request->input('sku_code');
		
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
		
		$cat = ['Fast', 'Medium', 'Slow'];
		// get performance master date
		$performancesdata = DB::table('performances')
							->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code $clscon GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as sale_qty"),'performances.category as percat','performances.product_code','performances.sale_through', DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as net_sale_qty"))
							->join("stocks","stocks.product_code","=","performances.product_code","INNER");
							
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
				if($val->closing_qty > 0){
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
			$stockreports = $perdata->sortBy('performance')->paginate(10)->setPath('');
			
			// bind value with pagination link
			$pagination = $stockreports->appends ( array (
				'lotno' => $lotno,
				'brand' => $brand,
				'category' => $category,
				'gender' => $gender,
				'colour' => $colour,
				'product_code' => $product_code,
				'from_date' => $from_date,
				'to_date' => $to_date,
				'percat' => $percat,
				'sku_code' => $sku_code,
			));
			
			return view('reports.performancereport',compact('stockreports', 'request', 'colour', 'brand', 'category', 'gender', 'lotno', 'product_code', 'from_date', 'to_date', 'percat', 'cat','sku_code'))
				->with('i', ($request->input('page', 1) - 1) * 10);
		}else{
			$items = [];
			$stockreports = (new Collection($items))->paginate(10)->setPath('');
			
			// bind value with pagination link
			$pagination = $stockreports->appends ( array (
				'lotno' => $lotno,
				'brand' => $brand,
				'category' => $category,
				'gender' => $gender,
				'colour' => $colour,
				'product_code' => $product_code,
				'from_date' => $from_date,
				'to_date' => $to_date,
				'percat' => $percat,
				'sku_code' => $sku_code,
			));
			
			return view('reports.performancereport',compact('stockreports', 'request', 'colour', 'brand', 'category', 'gender', 'lotno', 'product_code', 'from_date', 'to_date', 'percat', 'cat','sku_code'))
				->with('i', ($request->input('page', 1) - 1) * 10);
		}
    }
	
	 /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request)
    {	
        return Excel::download(new PerformanceReportExport($request), "performancereports.".$request['exporttype']);
    }
}
