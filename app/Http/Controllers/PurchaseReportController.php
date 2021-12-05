<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Vendor;
use App\Binding;
use App\Currencies;
use App\VendorStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\VendorStockExport;
use App\Exports\PurchaseReportExport;
use App\Imports\VendorStockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;
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
         $this->middleware('permission:vendor-stock-list|vendor-stock-create|vendor-stock-edit|vendor-stock-delete|vendor-stock-import-export', ['only' => ['index','store']]);
         $this->middleware('permission:vendor-stock-list', ['only' => ['index']]);
         $this->middleware('permission:vendor-stock-create', ['only' => ['create','store']]);
         $this->middleware('permission:vendor-stock-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:vendor-stock-delete', ['only' => ['destroy', 'deletestockall']]);
         $this->middleware('permission:vendor-stock-import-export', ['only' => ['stock-import-export','stockimport','stockexport']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$purchaseorders = [];
		$result = DB::table('purchase_orders')
		->join('customer_orders','order_item_id','purchase_orders.isbn13')
		->leftjoin('book_details','book_details.isbnno','purchase_orders.isbn13')
		->select('purchase_orders.isbn13','book_details.name',
		DB::raw("(sum(customer_orders.quantity_purchased) - sum(purchase_orders.quantity)) as quantity")
		)->groupby('purchase_orders.isbn13')->get();

		if($result->count() > 0){
			foreach($result as $value){
				if($value->quantity > 0){
					$flag = 0;
					$remainquantity = $value->quantity;
					//check priority wise						
					for($i=1; $i<=30; $i++){
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
								$remainquantity = ($value->quantity - $venderdetails[0]->quantity); 
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