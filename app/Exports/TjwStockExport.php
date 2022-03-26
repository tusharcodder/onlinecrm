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
		 $result = DB::table('warehouse_stocks',)
		->select('warehouses.name','warehouse_stocks.isbn13','book_details.name as book_title',
		DB::raw("(sum(case when warehouse_stocks.quantity is not null THEN warehouse_stocks.quantity else 0 END)-(IFNULL( ( SELECT sum(customer_orders.quantity_to_be_shipped) from customer_orders INNER join skudetails on skudetails.sku_code = customer_orders.sku where skudetails.isbn13 = warehouse_stocks.isbn13 and customer_orders.warehouse_id = warehouse_stocks.warehouse_id), 0))) as stock"))
		->leftJoin('book_details','book_details.isbnno','=','warehouse_stocks.isbn13')
        ->leftJoin('warehouses','warehouses.id','=','warehouse_stocks.warehouse_id') 
		->where(function($query) use ($search) {
			$query->where('warehouse_stocks.isbn13','LIKE','%'.$search.'%')
			->orWhere('warehouses.name','LIKE','%'.$search.'%')
			->orWhere('book_details.name','LIKE','%'.$search.'%');
		})		
		->groupby('warehouse_stocks.isbn13','warehouse_stocks.warehouse_id')  
		->orderBy('book_details.name','ASC')->get();			 	
            
        return view('stocks.exportstock', [
			'results' => $result,
			'exporttype' => $exporttype,
		]);
    }
}
