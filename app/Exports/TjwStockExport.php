<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class TjwStockExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
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
		$search = $this->request['hiddensearch'];	    
		
		 //get details
		 $stockreportarr = array();   
		//query for Box ISBN     
		$stocksboxisbn = DB::table('warehouse_stocks',)
		->select('warehouses.name','warehouses.id as warehouse_id','warehouse_stocks.isbn13 as isbnno','book_details.name as book_title','skudetails.type as isbntype',
        DB::raw("IFNULL(warehouse_stocks.quantity,0) as wareqty"),
        DB::raw("IFNULL(sum(customer_orders.quantity_to_be_shipped),0) as orderqty"),'warehouse_stocks.rack_location')
        ->leftJoin('box_child_isbns','box_child_isbns.book_isbn13','=','warehouse_stocks.isbn13')
        ->leftJoin('box_parent_isbns','box_parent_isbns.id','=','box_child_isbns.box_isbn_id')
		->leftJoin('skudetails','skudetails.isbn13','=','box_parent_isbns.box_isbn13')
        ->leftJoin('customer_orders','customer_orders.sku','=','skudetails.sku_code')
        ->leftJoin('warehouses','warehouses.id','=','warehouse_stocks.warehouse_id')
        ->leftJoin('book_details','book_details.isbnno','=','warehouse_stocks.isbn13')  
        ->where('skudetails.type','Box')        
		->where(function($query) use ($search) {
			$query->where('warehouse_stocks.isbn13','LIKE','%'.$search.'%')
			->orWhere('warehouses.name','LIKE','%'.$search.'%')
			->orWhere('warehouse_stocks.rack_location','LIKE','%'.$search.'%')
			->orWhere('book_details.name','LIKE','%'.$search.'%');
		})
		->groupby('warehouse_stocks.isbn13','warehouse_stocks.warehouse_id','warehouse_stocks.rack_location')
        ->orderBy('book_details.name','ASC');
        
		//query for Single ISBN
        $stocks = DB::table('warehouse_stocks',)
		->select('warehouses.name','warehouses.id as warehouse_id','warehouse_stocks.isbn13 as isbnno','book_details.name as book_title','skudetails.type as isbntype',
        DB::raw("IFNULL(warehouse_stocks.quantity,0) as wareqty"),
        DB::raw("IFNULL(sum(customer_orders.quantity_to_be_shipped),0) as orderqty"),'warehouse_stocks.rack_location')
        ->leftJoin('skudetails','skudetails.isbn13','=','warehouse_stocks.isbn13')  
        ->leftJoin('customer_orders','customer_orders.sku','=','skudetails.sku_code')
        ->leftJoin('warehouses','warehouses.id','=','warehouse_stocks.warehouse_id')
        ->leftJoin('book_details','book_details.isbnno','=','warehouse_stocks.isbn13')         
        
       
        ->where('skudetails.type','Single')        
		->where(function($query) use ($search) {
			$query->where('warehouse_stocks.isbn13','LIKE','%'.$search.'%')
			->orWhere('warehouses.name','LIKE','%'.$search.'%')
			->orWhere('warehouse_stocks.rack_location','LIKE','%'.$search.'%')
			->orWhere('book_details.name','LIKE','%'.$search.'%');
		})
		->groupby('warehouse_stocks.isbn13','warehouse_stocks.warehouse_id','warehouse_stocks.rack_location')
        ->orderBy('book_details.name','ASC')
        ->unionAll($stocksboxisbn)
        ->get();
        
        

        if(!empty($stocks)){
			$isbnstkqty = array();
            $warehouse_id = array();
			// add to be ship qty based on isbn
			foreach($stocks as $key => $val){
				$val->orderqty = empty($val->orderqty) ? 0 : (float)$val->orderqty; 
                $val->wareqty = empty($val->wareqty) ? 0 : (float)$val->wareqty; 
				if (array_key_exists($val->warehouse_id.'-'.$val->isbnno.'-'.$val->rack_location, $isbnstkqty)){
					$isbnstkqty[$val->warehouse_id.'-'.$val->isbnno.'-'.$val->rack_location]  = $isbnstkqty[$val->warehouse_id.'-'.$val->isbnno.'-'.$val->rack_location] +  $val->orderqty;
                    //$stock = ($val->wareqty - $val->orderqty);
				}
				else{
					$isbnstkqty[$val->warehouse_id.'-'.$val->isbnno.'-'.$val->rack_location]  =  $val->orderqty;
                   // $stock = ($val->wareqty - $val->orderqty);
				}
				
				$stockreportarr[$val->warehouse_id.'-'.$val->isbnno.'-'.$val->rack_location] = (object)([
					'name' => $val->name, 
					'isbn13' =>  $val->isbnno, 
					'book_title' => $val->book_title,
					'stock' => ($val->wareqty - $isbnstkqty[$val->warehouse_id.'-'.$val->isbnno.'-'.$val->rack_location]),
					'location' => $val->rack_location
					
				]);
			}
		}

        $result = collect($stockreportarr);		 	
            
        return view('stocks.exportstock', [
			'results' => $result,
			'exporttype' => $exporttype,
		]);
    }
}
