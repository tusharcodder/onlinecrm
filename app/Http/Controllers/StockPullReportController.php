<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Vendor;
use App\Binding;
use App\Currencies;
use App\VendorStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\StockPullReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;

class StockPullReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:stock-pull-report', ['only' => ['index']]);
		$this->middleware('permission:download-stock-pull-report', ['only' => ['export']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$stockpullreports = DB::table('customer_orders')
			->select('skudetails.isbn13 as isbnno','book_details.name as bookname', DB::raw('sum(purchase_orders.quantity) as purqty'), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT SUM(coshipqty.quantity_shipped) FROM order_tracking as coshipqty WHERE coshipqty.isbnno = skudetails.isbn13 GROUP BY coshipqty.isbnno) as shiped_qty"))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("purchase_orders","purchase_orders.isbn13","=","skudetails.isbn13")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)
			->groupBy('purchase_orders.isbn13')
			->orderBy('purchase_orders.isbn13','ASC')
			->paginate(10)
			->setPath('');
		
        return view('reports.stockpullreport',compact('stockpullreports'))
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
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 		//
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
	public function export(Request $request) 
    {	
		return Excel::download(new StockPullReportExport($request), "stockpullreport.".$request['exporttype']);
    }
}