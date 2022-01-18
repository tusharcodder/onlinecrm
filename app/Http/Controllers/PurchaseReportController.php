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
		->select('customer_orders.product_name','skudetails.isbn13','book_details.name','book_details.author', 'book_details.publisher' ,       
		DB::raw("(sum(customer_orders.quantity_to_ship) - ((IFNULL( ( SELECT sum(INO.quantity_to_be_shipped) from customer_orders INO where INO.warehouse_country_code = 'IN' and INO.sku = customer_orders.sku GROUP by INO.sku ), 0))-(IFNULL( ( SELECT warehouse_stocks.quantity from warehouse_stocks LEFT JOIN warehouses on warehouses.id = warehouse_stocks.warehouse_id where warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.country_code = 'IN' GROUP by warehouse_stocks.isbn13 ), 0)))) as quantity")
		)->where('customer_orders.quantity_to_ship', '!=', 0)
		 ->where('customer_orders.quantity_to_be_shipped', '=', 0)
		 ->groupby('customer_orders.sku')->get();
   

		if($result->count() > 0){
			foreach($result as $value){
				if($value->quantity > 0){
					$flag = 0;
                    $istrue = false;
					$remainquantity = $value->quantity;//set the quantity
					//get record priority wise						
					for($i=1; $i<=$maxpriority; $i++){
						$venderdetails = DB::table('vendor_stocks')
						->join('vendors','vendors.id','vendor_stocks.vendor_id')->select('vendors.name','author','publisher','quantity')
						->where('vendors.priority',$i)
                        ->where('vendor_stocks.isbnno',$value->isbn13)
                        ->where('quantity','>',0)
						->get();
						if($venderdetails->count() > 0){
                            $istrue = true;
							//check vendor quantity greater or equal to order
							if($venderdetails[0]->quantity >= $remainquantity){
								$flag = 1;
								$dataarray = array(
									"isbn13"=>$value->isbn13,
									'book'=>$value->product_name,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'quantity'=>$remainquantity,
									'vendor_name'=>$venderdetails[0]->name,
								);
								$purchaseorders[] =$dataarray;
							}
							else {
								// get remain quantity and check for next priority
								$remainquantity = ($remainquantity - $venderdetails[0]->quantity); 
								$dataarray = array(
									"isbn13"=>$value->isbn13,
									'book'=>$value->product_name,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'quantity'=> $venderdetails[0]->quantity,
									'vendor_name'=>$venderdetails[0]->name,
								);
								$purchaseorders[] =$dataarray;
							}
								
						}//break the loop
						if($flag > 0)
							break;
					}
                    //when isbn not in any vendor stock 
                    if(!$istrue){
                        $dataarray = array(
                            "isbn13"=>$value->isbn13,
                            'book'=>$value->product_name,
                            'author'=>$value->author,
                            'publisher'=>$value->publisher,
                            'quantity'=> $value->quantity,
                            'vendor_name'=>'',
                        );
                        $purchaseorders[] =$dataarray;
                    }

				}
					
				
			}         
		}
		$purchaseorders = (new Collection($purchaseorders))->sortBy('book')->paginate(10)->setPath('');
		return view('reports.purchasereport',compact('purchaseorders'))
		->with('i', ($request->input('page', 1) - 1) * 10);

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