<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class MultiPackagingReportExport implements FromView
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
			->select('customer_orders.sku as sku','skudetails.isbn13 as isbnno','book_details.name as bookname','customer_orders.order_id as order_id','customer_orders.order_item_id as order_item_id', DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'),'customer_orders.ship_country as ship_country','customer_orders.purchase_date as purchase_date')
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)
			->groupBy('customer_orders.order_id','customer_orders.order_item_id','skudetails.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')
			->having(DB::raw('sum(customer_orders.quantity_to_be_shipped)'), '>=' , 2)->get();
			
        return view('reports.exportmultipackagingreports', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
