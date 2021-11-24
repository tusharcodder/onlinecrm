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
use App\Exports\MultiPackagingReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;

class MultiPackagingReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:multi-packaging-report', ['only' => ['index']]);
		$this->middleware('permission:download-multi-packaging-report', ['only' => ['export']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$multipackreports = DB::table('customer_orders')
			->select('customer_orders.sku as sku','skudetails.isbn13 as isbnno','book_details.name as bookname','customer_orders.order_id as order_id','customer_orders.order_item_id as order_item_id', DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'),'customer_orders.ship_country as ship_country','customer_orders.purchase_date as purchase_date')
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)
			->groupBy('customer_orders.order_id','customer_orders.order_item_id', 'customer_orders.sku','skudetails.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')
			->having(DB::raw('sum(customer_orders.quantity_to_be_shipped)'), '>=' , 2)
			->paginate(10)
			->setPath('');
		
        return view('reports.multipackagingreport',compact('multipackreports'))
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
		return Excel::download(new MultiPackagingReportExport($request), "multipackagingreport.".$request['exporttype']);
    }
}