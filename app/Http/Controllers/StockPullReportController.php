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
			->select('customer_orders.warehouse_name','skudetails.isbn13 as isbnno','book_details.name as bookname', DB::raw("(SELECT SUM(warehouse_stocks.quantity) FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.is_shipped = '1' and warehouses.id = customer_orders.warehouse_id GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)      
			->groupBy('skudetails.isbn13','customer_orders.warehouse_id')
			->orderBy('skudetails.isbn13','ASC')
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