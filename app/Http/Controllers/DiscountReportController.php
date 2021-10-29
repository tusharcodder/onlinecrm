<?php

namespace App\Http\Controllers;

use App\DiscountReport;
use Illuminate\Http\Request;
use App\Discount;
use DB;
use App\Exports\DiscountReportExport;
use Maatwebsite\Excel\Facades\Excel;

class DiscountReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:discount-report-list', ['only' => ['index', 'search']]);
		$this->middleware('permission:download-discount-report', ['only' => ['downloaddiscountreport']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$type = '';
		$vendor = '';
		$aggregator_vendor = '';
		$lotno = '';
		$brand = '';
		$category = '';
		$gender = '';
		$colour = '';
		$product_code = '';
		$from_date = '';
		$to_date = '';
		
		$vtype = ['Aggregator', 'Online', 'SOR', 'Outride'];
		$discountreports = DB::table('discounts')
			->select('discounts.*','stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.vendor_type = discounts.vendor_type and sales.vendor_name = discounts.vendor_name and (sales.aggregator_vendor_name = discounts.aggregator_vendor_name or sales.aggregator_vendor_name is null) and sales.product_code = discounts.product_code GROUP BY sales.vendor_type,sales.vendor_name,sales.aggregator_vendor_name,sales.product_code) as sale_qty"),'stocks.image_url')
			->join("stocks","stocks.product_code","=","discounts.product_code","Inner")
			->groupBy('discounts.vendor_type','discounts.vendor_name','discounts.aggregator_vendor_name','discounts.product_code','discounts.valid_from_date')
			->orderBy('discounts.valid_from_date','DESC')
			->orderBy('stocks.id','DESC')
			->paginate(10)
			->setPath('');
		
        return view('reports.discountreport',compact('vtype', 'discountreports', 'request', 'type', 'vendor', 'aggregator_vendor', 'lotno', 'brand', 'category', 'gender', 'colour', 'product_code', 'from_date', 'to_date'))
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
    public function show(DiscountReport $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(DiscountReport $report)
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
    public function update(Request $request, DiscountReport $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscountReport $report)
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
		$type = $request->input('type');
		$vendor = $request->input('vendor');
		$aggregator_vendor = $request->input('aggregator_vendor');
		$lotno = $request->input('lotno');
		$brand = $request->input('brand');
		$category = $request->input('category');
		$gender = $request->input('gender');
		$colour = $request->input('colour');
		$product_code = $request->input('product_code');
		$from_date = $request->input('from_date');
		$to_date = $request->input('to_date');
		
		$vtype = ['Aggregator', 'Online', 'SOR', 'Outride'];
		$discountreports = DB::table('discounts')
			->select('discounts.*','stocks.brand','stocks.category','stocks.gender','stocks.colour','stocks.lotno','stocks.sku_code','stocks.hsn_code','stocks.online_mrp','stocks.offline_mrp','stocks.cost', DB::raw('sum(stocks.quantity) as quantity'), DB::raw("(SELECT SUM(sales.quantity) FROM sales WHERE sales.vendor_type = discounts.vendor_type and sales.vendor_name = discounts.vendor_name and (sales.aggregator_vendor_name = discounts.aggregator_vendor_name or sales.aggregator_vendor_name is null) and sales.product_code = discounts.product_code GROUP BY sales.vendor_type,sales.vendor_name,sales.aggregator_vendor_name,sales.product_code) as sale_qty"),'stocks.image_url')
			->join("stocks","stocks.product_code","=","discounts.product_code","Inner");
			
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
		$discountreports = 	$discountreports->paginate(10);
		$discountreports = 	$discountreports->setPath('');
		
		// bind value with pagination link
		$pagination = $discountreports->appends ( array (
			'type' => $type,
			'vendor' => $vendor,
			'aggregator_vendor' => $aggregator_vendor,
			'lotno' => $lotno,
			'brand' => $brand,
			'category' => $category,
			'gender' => $gender,
			'colour' => $colour,
			'product_code' => $product_code,
			'from_date' => $from_date,
			'to_date' => $to_date,
		));
		
        return view('reports.discountreport',compact('vtype', 'discountreports', 'request', 'type', 'vendor', 'aggregator_vendor', 'lotno', 'brand', 'category', 'gender', 'colour', 'product_code', 'from_date', 'to_date'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
	
	 /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request) 
    {	
        return Excel::download(new DiscountReportExport($request), "discountreports.".$request['exporttype']);
    }
}
