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
use App\Exports\ShippedOrderExport;
use App\Imports\ShipmentReportImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

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
		
		// before generate all quantity_to_be_shipped value should be zero
		DB::table('customer_orders')
		->update([
			'warehouse_id' => null,
			'warehouse_name' => null,
			'warehouse_country_code' => null,
			'quantity_to_be_shipped' => 0,
		]);
					
		// generate report 
		$shipmentres = DB::table('customer_orders')
			->select('customer_orders.*','market_places.name as markname','skudetails.isbn13 as isbnno','skudetails.pkg_wght as pkg_wght','skudetails.wght as wght','book_details.name as proname', 'book_details.author as author', 'book_details.publisher as publisher',DB::raw("(SELECT CONCAT(warehouse_stocks.warehouse_id,'-',warehouse_stocks.quantity,'-',warehouses.name) FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.country_code = customer_orders.ship_country and warehouses.is_shipped = '1' GROUP BY warehouse_stocks.warehouse_id, warehouse_stocks.isbn13 having sum(warehouse_stocks.quantity) >= customer_orders.quantity_to_ship LIMIT 1) as ostkqty"),DB::raw("(SELECT CONCAT(warehouse_stocks.warehouse_id,'-',warehouse_stocks.quantity,'-',warehouses.name) FROM warehouse_stocks left join warehouses on warehouses.id = warehouse_stocks.warehouse_id WHERE warehouse_stocks.isbn13 = skudetails.isbn13 and warehouses.country_code = 'IN' and warehouses.is_shipped = '1' GROUP BY warehouse_stocks.warehouse_id, warehouse_stocks.isbn13) as indstkqty"),'skudetails.oz_wt','skudetails.mrp')
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("market_places","market_places.id","=","skudetails.market_id")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->where('customer_orders.quantity_to_ship', '>' ,0)
			->groupBy('customer_orders.order_id', 'customer_orders.order_item_id', 'customer_orders.ship_country', 'skudetails.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')->get();

		if(!empty($shipmentres)){
			
			// get stock value based on isbn no
			foreach($shipmentres as $key => $val){
				$shipcountry = $val->ship_country;
				if(!empty($val->ostkqty)){// for order country code
					$ostkqty = $val->ostkqty;
					$ostkqty = explode("-",$ostkqty);
					
					$cowid = empty($ostkqty[0]) ? '' : $ostkqty[0];
					$costkqty = empty($ostkqty[1]) ? 0 : $ostkqty[1];
					$cowname = empty($ostkqty[2]) ? '' : $ostkqty[2];
					
					
					$isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wid'] = $cowid;
					$isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wname'] = $cowname;
					$isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wsqty'] = (float)$costkqty;
				}
				
				if(!empty($val->indstkqty)){// for India
					$indstkqty = $val->indstkqty;
					$indstkqty = explode("-",$indstkqty);
					
					$inwid = empty($indstkqty[0]) ? '' : $indstkqty[0];
					$instkqty = empty($indstkqty[1]) ? 0 : $indstkqty[1];
					$inwname = empty($indstkqty[2]) ? '' : $indstkqty[2];
					
					$isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wid'] = $inwid;
					$isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wname'] = $inwname;
					$isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wsqty'] = (float)$instkqty;
				}
			}
			
			
			foreach($shipmentres as $key => $val){
				$shipcountry = $val->ship_country;
				$quantity_to_ship = empty($val->quantity_to_ship) ? 0 : $val->quantity_to_ship;
				
				$stkqty = isset($isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wsqty']) ? $isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wsqty'] : 0;
				$instkqty =  isset($isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wsqty']) ? $isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wsqty'] : 0 ;
				
				$shipedqty = 0;
				$wid = '';
				$wname = '';
				$wccode = '';
				
				if($quantity_to_ship <= $stkqty && $shipcountry != "IN" && !empty($stkqty)){ // for order country code
				//	echo $shipedqty;
					$shipedqty = $quantity_to_ship;
					$wid = $isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wid'];
					$wname = $isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wname'];
					$wccode = $shipcountry;
					$isbnstkqty[$shipcountry.'-'.$val->isbnno.'-'.'wsqty'] = $stkqty - $quantity_to_ship;
				}elseif($quantity_to_ship <= $instkqty && !empty($instkqty)){ // for India
				//	echo 'hey';
					$shipedqty = $quantity_to_ship;
					$wid = $isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wid'];
					$wname = $isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wname'];
					$wccode = "IN";
					$isbnstkqty['IN'.'-'.$val->isbnno.'-'.'wsqty'] = $instkqty - $quantity_to_ship;
				}else{
					$shipedqty = 0;
				}
				
				// not empty shipped qty
				if(!empty($shipedqty)){
					
					//update shipqty into the quantity_to_be_shipped column
					DB::table('customer_orders')
					->where('order_id', $val->order_id)
					->where('order_item_id', $val->order_item_id)
					->where('sku', $val->sku)
					->update([
						'warehouse_id' => $wid,
						'warehouse_name' => $wname,
						'warehouse_country_code' => $wccode,
						'quantity_to_be_shipped' => $shipedqty,
					]);
									
					$finalarray[] = (object)([
						'isbnno' => $val->isbnno, 
						'sku' => $val->sku,
						//'proname' => $val->proname,
						'proname' => (!empty($val->proname)) ? $val->proname : $val->product_name,
						'author' => $val->author,
						'publisher' => $val->publisher,
						'order_id' => $val->order_id,
						'order_item_id' => $val->order_item_id,
						'purchase_date' => $val->purchase_date,
						'shipedqty' => $shipedqty,
						'ware_id' => $wid,
						'warename' => $wname,
						'wccode' => $wccode,
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
						'wght' => $val->wght,
						'ounce' => $val->oz_wt,
						'mrp' => $val->mrp
					]);
				}
			}
			//exit;
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
	public function shipmentTrackExport(Request $request) 
    {	
		return Excel::download(new ShippedOrderExport($request), "shippedorder.".$request['exporttype']);
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
	
	/**
    * @return \Illuminate\Support\Collection
    */
	public function downloadShipmentLabel(Request $request) 
    {
		// download shipment label based on the shipment records
		$results = DB::table('customer_orders')
			->select('customer_orders.*','market_places.name as markname','skudetails.isbn13 as isbnno','skudetails.pkg_wght as pkg_wght','skudetails.wght as wght','book_details.name as proname', 'book_details.author as author', 'book_details.publisher as publisher', DB::raw('sum(customer_orders.quantity_to_be_shipped) as shipingqty'),'skudetails.oz_wt','skudetails.mrp', 'book_details.publisher as publisher', 'warehouses.name as wname', 'warehouses.country_code as wcountry', 'warehouses.address as wadd', 'warehouses.city as wcity', 'warehouses.state as wstate', 'warehouses.postal_code as wpcode', 'warehouses.email as wemail', 'warehouses.phone as wphone')
			->leftJoin("skudetails","skudetails.sku_code","=","customer_orders.sku")
			->leftJoin("market_places","market_places.id","=","skudetails.market_id")
			->leftJoin("book_details","book_details.isbnno","=","skudetails.isbn13")
			->leftJoin("warehouses","warehouses.id","=","customer_orders.warehouse_id")
			//->where('customer_orders.ship_country', '=' ,'US')
			->where('customer_orders.quantity_to_ship', '>' ,0)
			->whereNull('customer_orders.label_pdf_url')
			->groupBy('customer_orders.order_id', 'customer_orders.order_item_id', 'skudetails.isbn13')
			->orderBy('customer_orders.reporting_date','ASC')
			->having(DB::raw('sum(customer_orders.quantity_to_be_shipped)'), '>' , 0)->get();
		
		$labelapiarr = array();
		if(!empty($results)){
			foreach($results as $val){
				$labelapiarr[$val->order_id][] = $val;
			}
		}

		if(!empty($labelapiarr)){
			foreach($labelapiarr as $val){
				$val[0]->wcountry = ($val[0]->wcountry == 'IN') ? "US" : "US";
				$val[0]->wname = "Ravi (NE Warehouse)";
				$labelvalarr = array();
				$labelvalarr['from'] = [
					"name" => $val[0]->wname,
					"company" => "NE  Warehouse.",
					"phone" => $val[0]->wphone,
					"email" =>  $val[0]->wemail,
					"street1" =>  $val[0]->wadd,
					"street2" => "",
					"street3" => "",
					"city" => $val[0]->wcity,
					"state" => $val[0]->wstate,
					"postal_code" => $val[0]->wpcode,
					"country" => $val[0]->wcountry,
					"residential" => false,
					"tax_id"=> ""
				];
				
				$labelvalarr['to'] = [
					"name"=>$val[0]->buyer_name,
					"company"=> "",
					"phone"=> $val[0]->buyer_phone_number,
					"email"=> $val[0]->buyer_email,
					"street1"=>$val[0]->ship_address_1,
					"street2"=> $val[0]->ship_address_2,
					"street3"=> $val[0]->ship_address_3,
					"city"=> $val[0]->ship_city,
					"state"=> substr($val[0]->ship_state, 0 ,2),
					"postal_code"=> $val[0]->ship_postal_code,
					"country"=> substr($val[0]->ship_country, 0 ,2),
					"residential"=> true,
					"tax_id"=> ""
				];
				
				$qty = 0;
				$oz_weight = 0;
				$maxsize = 100;
				$pronames = array();
				$order_item_id = array();
				$lengthsz = $maxsize / count($val) - 5;
				$lengthsz = round($lengthsz);
				foreach($val as $labelval){
					$labelval->shipingqty = empty($labelval->shipingqty) ? 0 : $labelval->shipingqty;
					$order_item_id[$labelval->order_item_id] = $labelval->order_item_id; 
					$oz_weight = $oz_weight + ((float)$labelval->shipingqty * (float)$labelval->oz_wt);
					$qty = $qty + (float)$labelval->shipingqty;
					$proname = empty($labelval->proname) ? $labelval->product_name : $labelval->proname;
					$pronames[$proname] = substr($proname, 0 ,$lengthsz);
					$labelvalarr['parcels'] = [
						"number"=> $qty,
						"code"=> "",
						"unit"=> "imperial",
						//"weight"=> $oz_weight,
						"weight"=> 10,
						"length"=> 10,
						"width"=> 8.5,
						"height"=> 2,
						"dg_code"=> null
					];
				}

				$labelvalarr['domestic_options_contents'] = join(",", $pronames);
				$this->labelAPICallback($labelvalarr, $labelval->order_id, $order_item_id);
			}
			return true;
		}else{
			return false;
		}
	}
	
	// call api method for label printing
	function labelAPICallback($val, $order_id, $order_item_arr = array()){
		$labeldate = date('Y-m-d');
		$token = 'Basic TlRRPS4rbHNORytJdVRpMzZWOHpjT0JFLzd2N1Axc3luWFh5c0VKL3pTaE41M3ZjPTo=';
		$from = json_encode($val['from']);
		$to = json_encode($val['to']);
		$parcels = json_encode($val['parcels']);
		$domestic_options = $val['domestic_options_contents'];
		
		$postfeilds = '{
			"date": "'.$labeldate.'",
			"service": "USPS",
			"from":'.$from.',
			"to": '.$to.',
			"type": "box",
			"parcels": [
				'.$parcels.'
			],
			"insurance": null,
			"references": [
				{
					"type": "po_number",
					"value": "'.$order_id.'",
				},
				{
					"type": "customer_ref",
					"value": "'.$domestic_options.'",
				}
			],
			"remarks": null,
			"signature": "none",
			"pickup": "dropoff",
			"domestic_options": {
                "value": {
                    "currency": "USD",
                    "amount": 0
                },
                "contents": "'.$domestic_options.'"
            },
			"international_options": null,
			"additional_options": null,
			"document_options": {
				"return": true,
				"label_format": "pdf",
				"medium": "url"
			},
			"notifications": null
		}';

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.ypn.io/v2/shipping/shipments',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$postfeilds,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: '.$token
		  ),
		));
		
		$response = curl_exec($curl);
		curl_close($curl);
		$apires = json_decode($response);
		if(!empty($apires) && empty($apires->errors)){
			$trackno = $apires->documents[0]->tracking_number;
			$pdfurl = $apires->documents[0]->url;
			
			foreach($order_item_arr as $val_item_id){
				// save this value on orderid and order item id
				DB::table('customer_orders')
				->where('order_id', strval($order_id))
				->where('order_item_id', strval($val_item_id))
				->update([
					'tracking_number' => $trackno,
					'label_pdf_url' => $pdfurl,
				]);
			}

		}
		return true;
	}
}