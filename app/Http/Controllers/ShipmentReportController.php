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
use Illuminate\Support\Collection;

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
		$this->middleware('permission:shipment-track-import', ['only' => ['shipment-track-import']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$isbnstkqty = array();
		$finalarray = array();
		
		$shipmentres = DB::table('customer_orders')
			->select('customer_orders.*','market_places.name as markname','warehouses.name as warename','skudetails.isbn13 as isbnno','skudetails.pkg_wght as pkg_wght','book_details.name as proname', 'book_details.author as author', 'book_details.publisher as publisher', DB::raw("(SELECT SUM(purchase_orders.quantity) FROM purchase_orders WHERE purchase_orders.isbn13 = skudetails.isbn13 GROUP BY purchase_orders.isbn13) as purqty"), DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'), DB::raw("(SELECT SUM(coshipqty.quantity_shipped) FROM order_tracking as coshipqty WHERE coshipqty.isbnno = skudetails.isbn13 GROUP BY coshipqty.isbnno) as shiped_qty"))
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("market_places","market_places.id","=","skudetails.market_id")
			->leftJoin("warehouses","warehouses.id","=","skudetails.warehouse_id")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_ship', '>' ,0)
			->groupBy('customer_orders.order_id', 'customer_orders.order_item_id', 'skudetails.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')->get();
		
		
		if(!empty($shipmentres)){
			
			// get stock value based on isbn no
			foreach($shipmentres as $key => $val){
				$val->purqty = empty($val->purqty) ? 0 : $val->purqty;
				$val->shiped_qty = empty($val->shiped_qty) ? 0 : $val->shiped_qty;
				$stkqty = $val->purqty - $val->shiped_qty;
				$isbnstkqty[$val->isbnno] = $stkqty;
			}
			foreach($shipmentres as $key => $val){
				$val->purqty = empty($val->purqty) ? 0 : $val->purqty;
				$val->shiped_qty = empty($val->shiped_qty) ? 0 : $val->shiped_qty;
				$quantity_to_ship = empty($val->quantity_to_ship) ? 0 : $val->quantity_to_ship;
				//$stkqty = $val->purqty - $val->shiped_qty;
				$stkqty = $isbnstkqty[$val->isbnno];
				if(!empty($stkqty) && $stkqty > 0 && !empty($val->isbnno)){
					
					$shipedqty = ($stkqty >= $quantity_to_ship) ? $quantity_to_ship : $stkqty;
					$isbnstkqty[$val->isbnno] = $stkqty - $quantity_to_ship;
					
					//update shipqty into the quantity_to_be_shipped column
					DB::table('customer_orders')
						->where('order_id', $val->order_id)
						->where('order_item_id', $val->order_item_id)
						->where('sku', $val->sku)
						->update(['quantity_to_be_shipped' => $shipedqty]);
									
					$finalarray[] = (object)([
						'isbnno' => $val->isbnno, 
						'sku' => $val->sku,
						'proname' => $val->proname,
						'author' => $val->author,
						'publisher' => $val->publisher,
						'order_id' => $val->order_id,
						'order_item_id' => $val->order_item_id,
						'purchase_date' => $val->purchase_date,
						'shipedqty' => $shipedqty,
						'warename' => $val->warename,
						'buyer_name' => $val->buyer_name,
						'recipient_name' => $val->recipient_name,
						'buyer_phone_number' => $val->buyer_phone_number,
						'ship_address_1' => $val->ship_address_1,
						'ship_address_2' => $val->ship_address_2,
						'ship_address_3' => $val->ship_address_3,
						'ship_city' => $val->ship_city,
						'ship_state' => $val->ship_state,
						'ship_postal_code' => $val->ship_postal_code,
						'ship_country' => $val->ship_country,
						'markname' => $val->markname,
						'ship_service_level' => $val->ship_service_level,
						'pkg_wght' => $val->pkg_wght
					]);
				}
			}
		}
		$shipmentreports = collect($finalarray)->paginate(10)->setPath('');
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
    public function shipmentTrackImport()
    {
		return view('reports.track-import');
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
		
		if($request->hasFile('importfile')){
			$extension = File::extension($request->importfile->getClientOriginalName());
			$filesize = File::size($request->importfile->getRealPath());
			$filetype = File::mimeType($request->importfile->getRealPath());
						
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {				
				try{
					// import data into the database
					$import = new ShipmentReportImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('shipment-track-import')
                        ->with('error',$ex->getMessage());
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('shipment-track-import')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('shipment-track-import')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('shipment-track-import')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('shipmentreport')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('shipment-track-import')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}