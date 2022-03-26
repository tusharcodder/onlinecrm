<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class StockPullReportExport implements FromView
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
		$results = DB::table('customer_orders')
		->select('skudetails.isbn13 as isbnno','book_details.name as bookname', DB::raw("(SELECT SUM(warehouse_stocks.quantity) FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.is_shipped = '1' GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'))
		->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
		->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
		->where('customer_orders.quantity_to_be_shipped', '>' ,0)
		->groupBy('skudetails.isbn13')
		->orderBy('skudetails.isbn13','ASC')->get();
			
        return view('reports.exportstockpullreports', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
