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
		->select('skudetails.isbn13 as isbnno','book_details.name as bookname', DB::raw('sum(purchase_orders.quantity) as purqty'), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT SUM(coshipqty.quantity_shipped) FROM order_tracking as coshipqty WHERE coshipqty.isbnno = skudetails.isbn13 GROUP BY coshipqty.isbnno) as shiped_qty"))
		->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
		->leftJoin("purchase_orders","purchase_orders.isbn13","=","skudetails.isbn13")
		->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
		->where('customer_orders.quantity_to_be_shipped', '>' ,0)
		->groupBy('purchase_orders.isbn13')
		->orderBy('purchase_orders.isbn13','ASC')->get();
			
        return view('reports.exportstockpullreports', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
