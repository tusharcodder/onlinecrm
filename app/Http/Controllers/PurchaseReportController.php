<?php

namespace App\Http\Controllers;
use App\Vendor;
use Illuminate\Http\Request;
use DB;
use App\Exports\PurchaseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Collection;

class PurchaseReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:purchase-report', ['only' => ['index']]);
		$this->middleware('permission:download-purchase-report', ['only' => ['export']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
		->where(function($query){
			$query->whereNull('customer_orders.warehouse_country_code')
			->orwhere('customer_orders.warehouse_country_code','IN');
		})  
		 ->groupby('skudetails.isbn13')->get();
		
		/* echo '<pre>';
		print_r($result);
		echo '<pre>';exit; */
		
		DB::statement('update vendor_stocks v1 inner join vendor_stocks v2 on v1.id=v2.id set v1.temp_quantity = v2.quantity'); 

		if($result->count() > 0){
			foreach($result as $value){
				$value->quantity = ((int)$value->cust_qty - (int)$value->quantity);
				if($value->quantity > 0){
					$flag = 0; 
                    $istrue = false;
					$remainquantity = $value->quantity;//set the quantity
					$updatequantity = 0;
					 					
					for($i=1; $i<=$maxpriority; $i++){
						
						//get the vendor's stock priority wise	
						$venderdetails = DB::table('vendor_stocks')
						->join('vendors','vendors.id','vendor_stocks.vendor_id')
						->select('vendor_stocks.vendor_id','vendors.name','author','publisher','vendor_stocks.temp_quantity as quantity','price')  
						->where('vendors.priority',$i)
                        ->where('vendor_stocks.isbnno',$value->isbn13)
						->where('vendor_stocks.temp_quantity','>',0)
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
							if($venderdetails[0]->quantity >=$remainquantity )
								$updatequantity = (($venderdetails[0]->quantity)-($remainquantity));
							else
								$updatequantity =0;
							
							//update vendor quantity
							DB::table('vendor_stocks')
							->where('vendor_id',$venderdetails[0]->vendor_id)
							->where('isbnno',$value->isbn13)
							->update(array('temp_quantity'=>$updatequantity));
												
								
						}
						//break the priority loop
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
		$purchaseorders = (new Collection($purchaseorders))->sortBy('vendor_name')->paginate(1000)->setPath('');
		return view('reports.purchasereport',compact('purchaseorders'));

	}	

	//download excel
	public function export(Request $request) 
    {				
		return Excel::download(new PurchaseReportExport($request), "PurchaseReport.".$request['exporttype']);	
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 		// 		
    }	

}