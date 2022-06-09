<?php

namespace App\Exports;

use App\PriceInventory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

//class CustomerOrderExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
class PriceInventoryExport implements FromView
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
        $queryforboxisbn = PriceInventory::select('price_inventory.sku','skudetails.type',
        DB::raw("(select sum(vendor_stocks.quantity) from vendor_stocks where vendor_stocks.isbnno in (select box_child_isbns.book_isbn13 from box_child_isbns where box_child_isbns.box_isbn_id = box_parent_isbns.id)) as 'market_qunatity'"),
        DB::raw("IFNULL((select sum(warehouse_stocks.quantity) from warehouse_stocks where warehouse_stocks.isbn13 in (select box_child_isbns.book_isbn13 from box_child_isbns where box_child_isbns.box_isbn_id = box_parent_isbns.id)  and warehouse_stocks.warehouse_id = 1),0) as 'stock_qty'"))
        ->join('skudetails','skudetails.sku_code','=', 'price_inventory.sku')
        ->join('box_parent_isbns','box_parent_isbns.box_isbn13','=', 'skudetails.isbn13')
        //->leftJoin("box_child_isbns","box_child_isbns.box_isbn_id","=","box_parent_isbns.id")
        //->join('warehouse_stocks','warehouse_stocks.isbn13','=', 'box_child_isbns.book_isbn13')       
        //->where('warehouse_stocks.warehouse_id',1)
        ->where('skudetails.type','Box');
        //->groupBy('price_inventory.sku');
			
		
    //     $query = PriceInventory::select('price_inventory.sku','skudetails.type',
    //     DB::raw("(select sum(vendor_stocks.quantity) from vendor_stocks where vendor_stocks.isbnno = skudetails.isbn13 ) as 'market_qunatity'"),
    //     DB::raw("IFNULL((select sum(warehouse_stocks.quantity) from warehouse_stocks where warehouse_stocks.isbn13 = skudetails.isbn13 and warehouse_stocks.warehouse_id = 1),0) as 'stock_qty'"))
    //     ->join('skudetails','skudetails.sku_code','=', 'price_inventory.sku')
    //     //->join('warehouse_stocks','warehouse_stocks.isbn13','=', 'skudetails.isbn13')       
    //    // ->where('warehouse_stocks.warehouse_id',1)
    //     ->where('skudetails.type','Single')
    //     ->groupBy('price_inventory.sku')
    //     ->union($queryforboxisbn);	
				
		$results = $queryforboxisbn->get();
        
        // if(!empty($results)){
		// 	$isbnstkqty = array();
		// 	// add to be ship qty based on isbn
		// 	foreach($results as $key => $val){
		// 		$val->market_qunatity = empty($val->market_qunatity) ? 0 : (float)$val->market_qunatity;
        //         $val->stock_qty = empty($val->stock_qty) ? 0 : (float)$val->stock_qty;

		// 		if (array_key_exists($val->sku, $isbnstkqty)){
		// 			$isbnstkqty[ $val->sku ]  =  $isbnstkqty[ $val->sku ] + $val->shipingqty;
		// 		}
		// 		else{
		// 			$isbnstkqty[ $val->sku ]  = $val->shipingqty;
		// 		}
				
		// 		$pullreportarr[$val->isbnno] = (object)([
		// 			'purqty' => $val->purqty, 
		// 			'shipingqty' => $isbnstkqty[ $val->isbnno ], 
		// 			'warehouse_name' => $val->warehouse_name,
		// 			'isbnno' => $val->isbnno,
		// 			'bookname' => $val->bookname,
		// 			'rack_details' => $val->rack_details,
		// 		]);
		// 	}
		// }    

		echo '<pre>';
        print_r((array)$results);
        echo '</pre>';
        exit;	
        return view('reports.exportpriceinventory', [
			'results' => $results,			
		]);
    }
}
