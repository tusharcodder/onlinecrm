<?php

namespace App\Exports;
use App\PurchaseOrder;
use App\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Support\Collection;

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
		 //get the max priority
		 $maxpriority = Vendor::max('priority');     

		 //create empty array  
		 $purchaseorders = [];
 
		 //get details
		$result = DB::table('customer_orders')     
        ->leftjoin('skudetails','skudetails.sku_code','customer_orders.sku')   
		->leftjoin('book_details','book_details.isbnno','skudetails.isbn13')       
		->select('skudetails.sku_code','customer_orders.product_name','skudetails.isbn13','book_details.name','book_details.author', 'book_details.publisher' ,       
		DB::raw("((sum(customer_orders.quantity_to_ship)) - (IFNULL( ( SELECT sum(INO.quantity_to_be_shipped) from customer_orders INO where INO.warehouse_country_code = 'IN' and INO.sku = customer_orders.sku and INO.quantity_to_be_shipped > 0 GROUP by INO.sku ), 0))) as cust_qty"),
		DB::raw("((IFNULL( ( SELECT warehouse_stocks.quantity from warehouse_stocks LEFT JOIN warehouses on warehouses.id = warehouse_stocks.warehouse_id where warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.country_code = 'IN' GROUP by warehouse_stocks.isbn13 ), 0))-(IFNULL( ( SELECT sum(INO.quantity_to_be_shipped) from customer_orders INO where INO.warehouse_country_code = 'IN' and INO.sku = customer_orders.sku and INO.quantity_to_be_shipped > 0 GROUP by INO.sku ), 0))) as quantity")
		)->where('customer_orders.quantity_to_ship', '!=', 0)   
		  
		 ->groupby('skudetails.isbn13')->get();
					
	/* 	//insert vendor stocks into temp table		
		DB::statement('update vendor_stocks v1 inner join vendor_stocks v2 on v1.id=v2.id set v1.temp_quantity = v2.quantity')	; */
		   
		 if($result->count() > 0){
			foreach($result as $value){
				$value->quantity = ((int)$value->cust_qty - (int)$value->quantity);
				if($value->quantity > 0){
					$flag = 0;
                    $istrue = false;
					$remainquantity = $value->quantity;//set the quantity
					$updatequantity = 0;
					//get record priority wise						
					for($i=1; $i<=$maxpriority; $i++){
						  
						$venderdetails = DB::table('vendor_stocks')
						->join('vendors','vendors.id','vendor_stocks.vendor_id')->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.quantity','price')
						->where('vendors.priority',$i)
                        ->where('vendor_stocks.isbnno',$value->isbn13)
						->where('vendor_stocks.quantity','>',0)
						->get();
						
						 //set the book name
						 $bookname = (!empty($value->name)) ? $value->name : $value->product_name;
						
						if($venderdetails->count() > 0){
							
                            //get the all vendor's stock
							$vendors = DB::table('vendor_stocks')
									->join('vendors','vendors.id','=','vendor_stocks.vendor_id')
									->where('vendor_stocks.isbnno',$value->isbn13)
									->where('vendors.id','!=',$venderdetails[0]->vendor_id)
									->select('vendors.name','vendor_stocks.quantity','vendor_stocks.price')
									->orderBy('vendors.priority','asc')
									->get(); 
							  
							$vendordata = '';
							
							foreach($vendors as $val){
								$vendordata = $vendordata.''. $val->name.'-'.$val->price.'-'.$val->quantity.',';
							}
							
							$flag = 1;
							
							$dataarray = array(
								'Sku'=>$value->sku_code,
								"isbn13"=>$value->isbn13,
								'book'=> $bookname ,
								 'mrp'=>$venderdetails[0]->price,
								'author'=>$venderdetails[0]->author,
								'publisher'=>$venderdetails[0]->publisher,
								'New'=>$remainquantity,
								'quantity'=>$venderdetails[0]->quantity,
								'vendor_name'=>$venderdetails[0]->name,
								'vendordata'=>$vendordata,
							); 
							$purchaseorders[] =$dataarray;
							
							//set updated quantity
							if($venderdetails[0]->quantity >= $remainquantity )
								$updatequantity = (($venderdetails[0]->quantity)-($remainquantity));
							else
								$updatequantity =0;
							
							//update vendor quantity
							DB::table('vendor_stocks')
							->where('vendor_id',$venderdetails[0]->vendor_id)
							->where('isbnno',$value->isbn13)
							->update(array('quantity'=>$updatequantity));						 
								
							
						}//break the loop
						if($flag > 0){
							$remainquantity = 0;
							break;
						}
							
					}
                    //when isbn not in any vendor stock 
                    if($flag == 0){	
						
						//get the all vendor's stock
							$vendors = DB::table('vendor_stocks')
									->join('vendors','vendors.id','=','vendor_stocks.vendor_id')
									->where('vendor_stocks.isbnno',$value->isbn13)									
									->select('vendors.name','vendor_stocks.quantity','vendor_stocks.price')
									->orderBy('vendors.priority','asc')
									->get(); 
							  
							$vendordata = '';
							
							foreach($vendors as $val){
								$vendordata = $vendordata.''. $val->name.'-'.$val->price.'-'.$val->quantity.',';
							}
					
                        $dataarray = array(
							'Sku'=>$value->sku_code,
                            "isbn13"=>$value->isbn13,
                            'book'=> $bookname ,
							'mrp'=>'NA',
                            'author'=>$value->author,
                            'publisher'=>$value->publisher,
							'New'=>$remainquantity,
                            'quantity'=> '0',							
                            'vendor_name'=>'NA',
							'vendordata'=>$vendordata,
                        ); 
                        $purchaseorders[] =$dataarray;
                    }

				}
					
				
			}         
		}	 	
            
        return view('reports.purchasereportexport', [
			'results' => (new Collection($purchaseorders))->sortBy('vendor_name'),
			'exporttype' => $exporttype,
		]);
    }
}
