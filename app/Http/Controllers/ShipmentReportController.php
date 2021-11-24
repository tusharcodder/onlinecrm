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
use App\Exports\ShipmentReportExport;
use App\Imports\ShipmentReportImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;

class ShipmentReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:shipment-report', ['only' => ['index']]);
		$this->middleware('permission:download-shipment-report', ['only' => ['export']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$shipmentreports = DB::table('customer_orders')
			->select('customer_orders.*','market_places.name as markname','warehouses.name as warename','skudetails.isbn13 as isbnno','skudetails.pkg_wght as pkg_wght','book_details.name as proname', 'book_details.author as author', 'book_details.publisher as publisher', DB::raw('sum(purchase_orders.quantity) as purqty'), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT SUM(coshipqty.quantity_shipped) FROM order_tracking as coshipqty WHERE coshipqty.isbnno = skudetails.isbn13 GROUP BY coshipqty.isbnno) as shiped_qty"))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("market_places","market_places.id","=","skudetails.market_id")
			->leftJoin("warehouses","warehouses.id","=","skudetails.warehouse_id")
			->leftJoin("purchase_orders","purchase_orders.isbn13","=","skudetails.isbn13")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_ship', '>' ,0)
			->groupBy('customer_orders.order_id', 'customer_orders.order_item_id', 'purchase_orders.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')
			->paginate(10)
			->setPath('');

        return view('reports.shipmentreport',compact('shipmentreports', 'request'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//
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
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function stockImportExport()
    {
		$vendor = Vendor::get();
		$currency = Currencies::get();
		$binding = Binding::get();
		return view('vendorstocks.import-export',compact('vendor','currency','binding'));
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
	public function export(Request $request) 
    {	
		return Excel::download(new ShipmentReportExport($request), "shipmentreport.".$request['exporttype']);
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
	public function import(Request $request) 
    {
		if($request->input('importtype') == "newimport"){ // for new import
			//validate required
			$this->validate($request,
				[
					'importfile' => 'required|max:512000',
				],
				[
					'importfile.required' => 'Please select file to import.',
					'importfile.max' => 'Please upload upto 500MB file.'
				]
			);
		}
		
		if($request->hasFile('importfile')){
			$extension = File::extension($request->importfile->getClientOriginalName());
			$filesize = File::size($request->importfile->getRealPath());
			$filetype = File::mimeType($request->importfile->getRealPath());
						
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {				
				try{
					// truncate table
					DB::table("vendor_stocks")->truncate();
					
					// import data into the database
					$import = new VendorStockImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('vendor-stock-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('vendor-stock-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('vendor-stock-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('vendor-stock-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('vendorstocks.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('vendor-stock-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}