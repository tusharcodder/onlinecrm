<?php

namespace App\Exports;
use App\PurchaseOrder;
use App\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseReportExport implements FromView
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
		$maxpriority = Vendor::max('priority');  
		$purchaseorders = [];
		$result = DB::table('warehouse_stocks')        
		->leftjoin('book_details','book_details.isbnno','warehouse_stocks.isbn13')
        ->leftjoin('warehouses','warehouses.id','warehouse_stocks.warehouse_id')
		->select('warehouse_stocks.isbn13','book_details.name',
		DB::raw("((IFNULL( ( SELECT sum(customer_orders.quantity_to_ship) from customer_orders INNER join skudetails on skudetails.sku_code = customer_orders.sku where skudetails.isbn13 = warehouse_stocks.isbn13 GROUP by skudetails.isbn13 ), 0)-(IFNULL( ( SELECT sum(customer_orders.quantity_to_be_shipped) from customer_orders INNER join skudetails on skudetails.sku_code = customer_orders.sku where skudetails.isbn13 = warehouse_stocks.isbn13 GROUP by skudetails.isbn13 ), 0)) -(IFNULL( ( SELECT sum(customer_orders.quantity_to_be_shipped) from customer_orders INNER join skudetails on skudetails.sku_code = customer_orders.sku where skudetails.isbn13 = warehouse_stocks.isbn13 and customer_orders.warehouse_country_code='IN' GROUP by skudetails.isbn13 ), 0)- sum(warehouse_stocks.quantity)))) as quantity")
		)->where('warehouses.id',1)
        ->groupby('warehouse_stocks.isbn13')->get();


		if($result->count() > 0){
			foreach($result as $value){
				if($value->quantity > 0){
					$flag = 0;
					$remainquantity = $value->quantity;
					//check priority wise						
					for($i=1; $i<=$maxpriority; $i++){
						$venderdetails = DB::table('vendor_stocks')
						->join('vendors','vendors.id','vendor_stocks.vendor_id')->select('vendors.name','author','publisher','quantity')
						->where('vendors.priority',$i)->where('vendor_stocks.isbnno',$value->isbn13)
						->get();
						if($venderdetails->count() > 0){
							//check vendor have quantity more then need
							if($venderdetails[0]->quantity >= $remainquantity){
								$flag = 1;
								$dataarray = array(
									"isbn13"=>$value->isbn13,
									'book'=>$value->name,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'quantity'=>$remainquantity,
									'vendor_name'=>$venderdetails[0]->name,
								);
								$purchaseorders[] =$dataarray;
							}
							else {
								// get remain quantity and check in other vendor stock
								$remainquantity = ($remainquantity - $venderdetails[0]->quantity); 
								$dataarray = array(
									"isbn13"=>$value->isbn13,
									'book'=>$value->name,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'quantity'=> $venderdetails[0]->quantity,
									'vendor_name'=>$venderdetails[0]->name,
								);
								$purchaseorders[] =$dataarray;
							}
								
						}
						if($flag > 0)
							break;
					}

				}
					
				
			}         
		}   	
            
        return view('reports.purchasereportexport', [
			'results' => $purchaseorders,
			'exporttype' => $exporttype,
		]);
    }
}
