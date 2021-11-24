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
		
    
        // get details of discounts from query
        $result = DB::table('purchase_orders')
		->join('customer_orders','order_item_id','purchase_orders.isbn13')
		->leftjoin('book_details','book_details.isbnno','purchase_orders.isbn13')
		->select('purchase_orders.isbn13','book_details.name',
		DB::raw("(sum(customer_orders.quantity_purchased) - sum(purchase_orders.quantity)) as quantity")
		)->groupby('purchase_orders.isbn13')->get();

		if($result->count() > 0){
				$purchaseorders = [];
				foreach($result as $value){
					if($value->quantity > 0){
						//check priority wise
						$flag = 0;
						for($i=1;$i<10;$i++){
								$venderdetails = DB::table('vendor_stocks')
								->join('vendors','vendors.id','vendor_stocks.vendor_id')->select('vendors.name','author','publisher')
								->where('vendors.priority',$i)->where('vendor_stocks.isbnno',$value->isbn13)
								->get();
								if($venderdetails->count() > 0){
										$flag = 1;								
										$dataarray = array(
												"isbn13"=>$value->isbn13,
												'book'=>$value->name,
												'author'=>$venderdetails[0]->author,
												'publisher'=>$venderdetails[0]->publisher,
												'quantity'=>$value->quantity,
												'vendor_name'=>$venderdetails[0]->name,
										);
										$purchaseorders[] =$dataarray;
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
