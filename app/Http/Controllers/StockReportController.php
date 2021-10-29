<?php

namespace App\Http\Controllers;

use App\StockReport;
use Illuminate\Http\Request;
use App\Stock;
use DB;
use App\Exports\StockReportExport;
use Maatwebsite\Excel\Facades\Excel;

class StockReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:stock-report-list', ['only' => ['index', 'search']]);
		$this->middleware('permission:download-stock-report', ['only' => ['downloadstockreport']]);
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
		$sku_code = '';
		
		$stockreports = DB::table('stocks')
			->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.product_code','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code GROUP BY sales.product_code) as sale_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code AND sales.sku_code = stocks.sku_code GROUP BY sales.product_code, sales.sku_code) as net_sale_qty"))
			->groupBy('stocks.product_code', 'stocks.sku_code')
			->orderBy('stocks.stock_date','DESC')
			->paginate(10)
			->setPath('');
		
        return view('reports.stockreport',compact('stockreports', 'request', 'colour', 'brand', 'category', 'gender', 'lotno', 'product_code', 'from_date', 'to_date', 'sku_code'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
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
		$sku_code = $request->input('sku_code');
		
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
			->select('stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'),'stocks.image_url','stocks.product_code','stocks.stock_date','stocks.size','stocks.description', DB::raw("(SELECT SUM(clstock.quantity) FROM stocks as clstock WHERE clstock.product_code = stocks.product_code $clscon GROUP BY clstock.product_code) as closing_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code $salcon GROUP BY sales.product_code) as sale_qty"), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.product_code = stocks.product_code AND sales.sku_code = stocks.sku_code $salcon GROUP BY sales.product_code, sales.sku_code) as net_sale_qty"));
			
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

		$stockreports = $stockreports->groupBy('stocks.product_code', 'stocks.sku_code');
		$stockreports = $stockreports->orderBy('stocks.stock_date','DESC');
		$stockreports = $stockreports->paginate(10);
		$stockreports = $stockreports->setPath('');
		
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
			'sku_code' => $sku_code,
		));
		
        return view('reports.stockreport',compact('stockreports', 'request', 'lotno', 'brand', 'category', 'gender', 'colour', 'product_code', 'from_date', 'to_date', 'sku_code'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
	
	 /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request)
    {	
        return Excel::download(new StockReportExport($request), "stockreports.".$request['exporttype']);
    }
}
