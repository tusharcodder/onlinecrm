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
use Illuminate\Support\Collection;
 
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
		$pullreportarr = array();
		$stockpullreportsbox = DB::table('customer_orders')
			->select('customer_orders.warehouse_name','box_parent_isbns.box_isbn13 as box_isbn','box_child_isbns.book_isbn13 as isbnno','book_details.name as bookname','skudetails.type as isbntype', DB::raw("(SELECT warehouse_stocks.quantity FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = box_child_isbns.book_isbn13 and warehouses.is_shipped = '1' and warehouses.id = customer_orders.warehouse_id GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT GROUP_CONCAT(rack,'-',cast(total_qty as char)) FROM (SELECT s.isbn,s.rack,SUM(s.quantity) total_qty FROM order_shipped_isbn_rack_qty s LEFT JOIN warehouses i ON i.id=s.warehouse_id GROUP by s.rack, s.isbn) s WHERE isbn=box_child_isbns.book_isbn13) as rack_details"))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("box_parent_isbns","box_parent_isbns.box_isbn13","=","skudetails.isbn13")
			->leftJoin("box_child_isbns","box_child_isbns.box_isbn_id","=","box_parent_isbns.id")
			->leftJoin("book_details","book_details.isbnno","=","box_child_isbns.book_isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)
			->where('skudetails.type','=', 'Box')
			->groupBy('skudetails.isbn13','box_child_isbns.book_isbn13','customer_orders.warehouse_id')
			->orderBy('box_child_isbns.book_isbn13','ASC');
			
		$stockpullreports = DB::table('customer_orders')
			->select('customer_orders.warehouse_name',DB::raw('"-" as box_isbn'),'skudetails.isbn13 as isbnno','book_details.name as bookname','skudetails.type as isbntype', DB::raw("(SELECT warehouse_stocks.quantity FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.is_shipped = '1' and warehouses.id = customer_orders.warehouse_id GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT GROUP_CONCAT(rack,'-',cast(total_qty as char)) FROM (SELECT s.isbn,s.rack,SUM(s.quantity) total_qty FROM order_shipped_isbn_rack_qty s LEFT JOIN warehouses i ON i.id=s.warehouse_id GROUP by s.rack, s.isbn) s WHERE isbn=skudetails.isbn13) as rack_details"))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)
			->where('skudetails.type','=', 'Single')
			->groupBy('skudetails.isbn13','customer_orders.warehouse_id')
			->orderBy('skudetails.isbn13','ASC')
			->unionAll($stockpullreportsbox)
			->get();
			
		if(!empty($stockpullreports)){
			$isbnstkqty = array();
			// add to be ship qty based on isbn
			foreach($stockpullreports as $key => $val){
				$val->shipingqty = empty($val->shipingqty) ? 0 : (float)$val->shipingqty;
				if (array_key_exists($val->isbnno, $isbnstkqty)){
					$isbnstkqty[ $val->isbnno ]  =  $isbnstkqty[ $val->isbnno ] + $val->shipingqty;
				}
				else{
					$isbnstkqty[ $val->isbnno ]  = $val->shipingqty;
				}
				
				$pullreportarr[$val->isbnno] = (object)([
					'purqty' => $val->purqty, 
					'shipingqty' => $isbnstkqty[ $val->isbnno ], 
					'warehouse_name' => $val->warehouse_name,
					'box_isbn' => $val->box_isbn,
					'isbnno' => $val->isbnno,
					'bookname' => $val->bookname,
					'rack_details' => $val->rack_details,
				]);
			}
		}
		
		$stockpullreports = collect($pullreportarr)->paginate(10)->setPath('');
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