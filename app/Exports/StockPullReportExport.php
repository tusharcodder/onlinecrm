<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Illuminate\Support\Collection;

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
		/* $results = DB::table('customer_orders')
		->select('customer_orders.warehouse_name','skudetails.isbn13 as isbnno','book_details.name as bookname', DB::raw("(SELECT SUM(warehouse_stocks.quantity) FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.is_shipped = '1' GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'))
		->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
		->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
		->where('customer_orders.quantity_to_be_shipped', '>' ,0)
		->groupBy('skudetails.isbn13')
		->orderBy('skudetails.isbn13','ASC')->get(); */
		
		$pullreportarr = array();
		$stockpullreportsbox = DB::table('customer_orders')
			->select('customer_orders.warehouse_name','box_child_isbns.book_isbn13 as isbnno','book_details.name as bookname','skudetails.type as isbntype', DB::raw("(SELECT warehouse_stocks.quantity FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = box_child_isbns.book_isbn13 and warehouses.is_shipped = '1' and warehouses.id = customer_orders.warehouse_id GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT GROUP_CONCAT(rack,'-',cast(total_qty as char)) FROM (SELECT s.isbn,s.rack,SUM(s.quantity) total_qty FROM order_shipped_isbn_rack_qty s LEFT JOIN warehouses i ON i.id=s.warehouse_id GROUP by s.rack, s.isbn) s WHERE isbn=box_child_isbns.book_isbn13) as rack_details"))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("box_parent_isbns","box_parent_isbns.box_isbn13","=","skudetails.isbn13")
			->leftJoin("box_child_isbns","box_child_isbns.box_isbn_id","=","box_parent_isbns.id")
			->leftJoin("book_details","book_details.isbnno","=","box_child_isbns.book_isbn13")
			->where('customer_orders.quantity_to_be_shipped', '>' ,0)
			->where('skudetails.type','=', 'Box')
			->groupBy('skudetails.isbn13','box_child_isbns.book_isbn13','customer_orders.warehouse_id')
			->orderBy('box_child_isbns.book_isbn13','ASC');
			
		$stockpullreports = DB::table('customer_orders')
			->select('customer_orders.warehouse_name','skudetails.isbn13 as isbnno','book_details.name as bookname','skudetails.type as isbntype', DB::raw("(SELECT warehouse_stocks.quantity FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.is_shipped = '1' and warehouses.id = customer_orders.warehouse_id GROUP BY warehouse_stocks.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT GROUP_CONCAT(rack,'-',cast(total_qty as char)) FROM (SELECT s.isbn,s.rack,SUM(s.quantity) total_qty FROM order_shipped_isbn_rack_qty s LEFT JOIN warehouses i ON i.id=s.warehouse_id GROUP by s.rack, s.isbn) s WHERE isbn=skudetails.isbn13) as rack_details"))
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
					$isbnstkqty[][ $val->isbnno ] =  $isbnstkqty[ $val->isbnno ] + $val->shipingqty;
				}
				else{
					$isbnstkqty[ $val->isbnno ]  = $val->shipingqty;
				}
				
				$pullreportarr[$val->isbnno] = (object)([
					'purqty' => $val->purqty, 
					'shipingqty' => $isbnstkqty[ $val->isbnno ], 
					'warehouse_name' => $val->warehouse_name,
					'isbnno' => $val->isbnno,
					'bookname' => $val->bookname,
					'rack_details' => $val->rack_details,
				]);
			}
		}
		
		$results = collect($pullreportarr);
			
        return view('reports.exportstockpullreports', [
			'results' => $results,
			'exporttype' => $exporttype,
		]);
    }
}
