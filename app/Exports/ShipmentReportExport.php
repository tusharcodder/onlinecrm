<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class ShipmentReportExport implements FromView
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
			->select('customer_orders.*','market_places.name as markname','skudetails.isbn13 as isbnno','skudetails.pkg_wght as pkg_wght','skudetails.wght as wght','book_details.name as proname', 'book_details.author as author', 'book_details.publisher as publisher', DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'),'skudetails.oz_wt','skudetails.mrp')
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("market_places","market_places.id","=","skudetails.market_id")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_ship', '>' ,0)
			->groupBy('customer_orders.order_id', 'customer_orders.order_item_id', 'skudetails.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')
			->having(DB::raw('sum(customer_orders.quantity_to_be_shipped)'), '>' , 0)->get();
			
      return view('reports.exportshipmentreports', [
        'results' => $results,
        'exporttype' => $exporttype,
      ]);
    }	
}
