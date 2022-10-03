<?php

namespace App\Http\Controllers;

use App\CustomerOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;
use App\Exports\CustomerOrderExport;
use App\Imports\CustomerOrderImport;
use App\Imports\CustomerOrderReportImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;
use File;

class CustomerOrderController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:customer-order-list', ['only' => ['index']]);
		$this->middleware('permission:customer-order-delete-refund', ['only' => ['destroy']]);
		$this->middleware('permission:customer-order-import-export', ['only' => ['customer-order-import-export','customerorderimport','customerorderreportimport','customerorderexport']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$search = $request->input('search');
		$status = $request->input('status');
        //
		$customerorders = CustomerOrder::select('customer_orders.*','order_tracking.shipper_tracking_id','order_tracking.tracking_message')
					->leftjoin('order_tracking', 'order_tracking.order_item_id', '=', 'customer_orders.order_item_id' )
					->where(function($query) use ($search) {
					$query->Where('customer_orders.order_id','LIKE','%'.$search.'%')
					->orWhere(DB::raw("DATE_FORMAT(customer_orders.purchase_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
					->orWhere(DB::raw("DATE_FORMAT(customer_orders.payments_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
					->orWhere(DB::raw("DATE_FORMAT(customer_orders.reporting_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
					->orWhere('customer_orders.sku','LIKE','%'.$search.'%')
					->orWhere('customer_orders.product_name','LIKE','%'.$search.'%')
					->orWhere('customer_orders.quantity_purchased','LIKE','%'.$search.'%')
					->orWhere('customer_orders.buyer_name','LIKE','%'.$search.'%')
					->orWhere('customer_orders.buyer_phone_number','LIKE','%'.$search.'%')
					->orWhere('customer_orders.order_item_id','LIKE','%'.$search.'%')
					->orWhere('customer_orders.tracking_number','LIKE','%'.$search.'%')
					->orWhere('order_tracking.shipper_tracking_id','LIKE','%'.$search.'%');
				})->orderBy('customer_orders.purchase_date','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $customerorders->appends ( array (
			'search' => $search
		));
		
        return view('customerorders.index',compact('customerorders','search'))
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
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customerorders = CustomerOrder::select('customer_orders.*','customer_orders.order_id as cust_order_id','customer_orders.order_item_id as cust_order_item_id','order_tracking.*')
		->leftjoin('order_tracking', 'order_tracking.order_item_id', '=', 'customer_orders.order_item_id' )
		->where('customer_orders.id', $id)
		->get();
		return view('customerorders.show',compact('customerorders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerOrder $customerOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerOrder $customerOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		// delete row
		//DB::table("customer_orders")->where('id',$id)->delete();
		
		$user = Auth::user();
		$uid = $user->id;
		
		// update quantity 0
		$customerorders = CustomerOrder::find($id);
        $customerorders->quantity_shipped = 0;
        $customerorders->quantity_to_ship = 0;
        $customerorders->quantity_to_be_shipped = 0;
        $customerorders->status = 0;
        $customerorders->updated_by = $uid;
        $customerorders->save();

		// delete track details from order_tracking table
		//DB::table("order_tracking")->where('order_id',$customerorders["order_id"])->delete();
		
		// update quantity 0
		DB::table('order_tracking')
              ->where('order_id', $customerorders["order_id"])
              ->update(['quantity_shipped' => 0]);
			  
        return redirect()->route('customerorders.index')
                        ->with('success','Customer order deleted successfully.');
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function customerOrderImportExport()
    {
		return view('customerorders.import-export');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request) 
    {	
			
		return Excel::download(new CustomerOrderExport($request), "customerorders.".$request['exporttype']);
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request) 
    {
		 //
		$user = Auth::user();
		$uid = $user->id;
		
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
			
			if($extension == "txt"){ // for text file
				// get the contents of file in array
				//$filedata = File::get($request->importfile->getRealPath());
				
				//start read file data
				$filedata = fopen($request->importfile->getRealPath(), "r");
				$tab = "\t";
				$dataarray = array();
				while ( !feof($filedata) )
				{
					$line = fgets($filedata, 2048);
					if(!empty($line))
						$dataarray[] = str_getcsv($line, $tab);
				}
				fclose($filedata);
				//end read file data and store into variable array
				
				// make order data array from text file
				$orderdata = array();
				$datalength = count($dataarray);
				for($i = 1;$i < $datalength;$i++){
					$obj = array();
					for($j=0;$j<count($dataarray[$i]);$j++){
						$obj[$dataarray[0][$j]] = $dataarray[$i][$j];
					}
					$orderdata[] = $obj;
				}
				
				// add data into order table
				if(!empty($orderdata)){
					
					foreach($orderdata as $key => $val){
						// check duplicate order id exits or not
						$customerdata = CustomerOrder::where('order_id', '=', $val['order-id'])->where('order_item_id', '=', $val['order-item-id'])->get();
						
						if(empty(count($customerdata))){ // not inserted duplicated data
							CustomerOrder::create([
								'order_id' => $val['order-id'],
								'order_item_id' => $val['order-item-id'],
								'purchase_date' => $val['purchase-date'],
								'payments_date' => $val['payments-date'],
								'reporting_date' => $val['reporting-date'],
								'promise_date' => $val['promise-date'],
								'days_past_promise' => $val['days-past-promise'],
								'buyer_email' => $val['buyer-email'],
								'buyer_name' => $val['buyer-name'],
								'buyer_phone_number' => $val['buyer-phone-number'],
								'sku' => $val['sku'],
								'product_name' => $val['product-name'],
								'quantity_purchased' => $val['quantity-purchased'],
								'quantity_shipped' => $val['quantity-shipped'],
								'quantity_to_ship' => $val['quantity-to-ship'],
								'ship_service_level' => $val['ship-service-level'],
								'recipient_name' => $val['recipient-name'],
								'ship_address_1' => $val['ship-address-1'],
								'ship_address_2' => $val['ship-address-2'],
								'ship_address_3' => $val['ship-address-3'],
								'ship_city' => $val['ship-city'],
								'ship_state' => $val['ship-state'],
								'ship_postal_code' => $val['ship-postal-code'],
								'ship_country' => $val['ship-country'],
								'is_business_order' => $val['is-business-order'],
								'purchase_order_number' => $val['purchase-order-number'],
								'price_designation' => $val['price-designation'],
								'created_by' => $uid,
								'updated_by' => $uid
							]);
						}
					}
				}
				return redirect()->route('customerorders.index')
                        ->with('success','Your Data has successfully imported.');
			}elseif ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {	// for excel
				try{
					// import data into the database
					$import = new CustomerOrderImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('customer-order-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('customer-order-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){					
					return redirect()->route('customer-order-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('customer-order-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('customerorders.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('customer-order-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv/txt file..!!');
			} 
		}
    }

	//order reshipped
	public function orderReshipped($id){

		try{
			$user = Auth::user();
			$uid = $user->id;

			$order = CustomerOrder::find($id);
			//count how many time order shipped
			$count = DB::table('customer_orders')
					->select(DB::raw("count(id) as count"))
					->where('order_id',$order->order_id)
					->get();
			$insertarray = array(
				'order_id'=>$order->order_id,
				'order_item_id'=>($order->order_item_id.'-'.$count[0]->count),
				'purchase_date'=>$order->purchase_date,
				'payments_date'=>$order->payments_date,
				'reporting_date'=>$order->reporting_date,
				'promise_date'=>$order->promise_date,
				'days_past_promise'=>$order->days_past_promise,
				'buyer_email'=>$order->buyer_email,
				'buyer_name'=>$order->buyer_name,
				'buyer_phone_number'=>$order->buyer_phone_number,
				'sku'=>$order->sku,
				'product_name'=>$order->product_name,
				'quantity_purchased'=>$order->quantity_purchased,
				'quantity_shipped'=>0,
				'quantity_to_ship'=>$order->quantity_shipped,
				'quantity_to_be_shipped'=>0,
				'ship_service_level'=>$order->ship_service_level,
				'recipient_name'=>$order->recipient_name,
				'ship_address_1'=>$order->ship_address_1,
				'ship_address_2'=>$order->ship_address_2,
				'ship_address_3'=>$order->ship_address_3,
				'ship_city'=>$order->ship_city,
				'ship_state'=>$order->ship_state,
				'ship_postal_code'=>$order->ship_postal_code,
				'ship_country'=>$order->ship_country,
				'is_business_order'=>$order->is_business_order,
				'purchase_order_number'=>$order->purchase_order_number,
				'price_designation'=>$order->price_designation,
				'created_by'=>$uid ,
				'updated_by'=>$uid ,
				'created_at'=>now(),
				'updated_at'=>now(),			
			);	
			
			DB::table('customer_orders')->insert($insertarray);
			return redirect()->route('customerorders.index')
                        ->with('success','Order Created Successfully');
		}
		catch(\Exception $ex){
			return redirect()->route('customerorders.index')
                        ->with('error',$ex->getMessage());
		}
		
	}
	
	//cancel Shipment
	public function cancelShipmentLabel($id){
		  
		try{
			$user = Auth::user();
			$uid = $user->id;

			$order = CustomerOrder::find($id);
			if(!empty($order)){
				
				$token = 'Basic TlRRPS4rbHNORytJdVRpMzZWOHpjT0JFLzd2N1Axc3luWFh5c0VKL3pTaE41M3ZjPTo=';
				
				// cancel shipment label
				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.ypn.io/v2/shipping/shipments/".$order->label_id,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'DELETE',
				  //CURLOPT_POSTFIELDS =>$postfeilds,
				  CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'Authorization: '.$token
				  ),
				));
				$response = curl_exec($curl);
				curl_close($curl);				
				$apires = json_decode($response);
				
				if($apires->success){
					// update value of label id
					CustomerOrder::where('label_id', $order->label_id)
				   ->update([
					   'tracking_number' => NULL,
					   'label_pdf_url' => NULL,
					   'pdf_attachment_code' => NULL,
					   'deleted_at' => date('Y-m-d H:i:s'),
					   'deleted_by' => $uid,
					]);
				}
			}
			
			return redirect()->back()
                        ->with('success','Shipment Label Cancelled Successfully.');
		}
		catch(\Exception $ex){
			return redirect()->route('customerorders.index')
                        ->with('error',$ex->getMessage());
		}
		
	}
	
	 /**
    * @return \Illuminate\Support\Collection
    */
    public function reportImport(Request $request) 
    {
		 //
		$user = Auth::user();
		$uid = $user->id;
		
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
			
			if($extension == "txt"){ // for text file
				// get the contents of file in array
				//$filedata = File::get($request->importfile->getRealPath());
				
				//start read file data
				$filedata = fopen($request->importfile->getRealPath(), "r");
				$tab = "\t";
				$dataarray = array();
				while ( !feof($filedata) )
				{
					$line = fgets($filedata, 2048);
					if(!empty($line))
						$dataarray[] = str_getcsv($line, $tab);
				}
				fclose($filedata);
				//end read file data and store into variable array
				
				// make order data array from text file
				$orderdata = array();
				$datalength = count($dataarray);
				for($i = 1;$i < $datalength;$i++){
					$obj = array();
					for($j=0;$j<count($dataarray[$i]);$j++){
						$obj[$dataarray[0][$j]] = $dataarray[$i][$j];
					}
					$orderdata[] = $obj;
				}
				
				// add data into order table
				if(!empty($orderdata)){
					
					foreach($orderdata as $key => $val){
						// check duplicate order id exits or not
						$customerdata = CustomerOrder::where('order_id', '=', $val['order-id'])->where('order_item_id', '=', $val['order-item-id'])->get();
						
						if(count($customerdata) > 0){ // not inserted duplicated data
							CustomerOrder::where('order_id', '=', $val['order-id'])
							  ->where('order_item_id', '=', $val['order-item-id'])
							  ->update([
								'currency' => $val['currency'],
								'item_price' => $val['item-price'],
								'item_tax' => $val['item-tax'],
								'sales_channel' => $val['sales-channel'],
								'earliest_ship_date' => $val['earliest-ship-date'],
								'latest_ship_date' => $val['latest-ship-date'],
								'earliest_delivery_date' => $val['earliest-delivery-date'],
								'latest_delivery_date' => $val['latest-delivery-date'],
								'updated_by' => $uid,
								'updated_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				return redirect()->route('customerorders.index')
                        ->with('success','Your Data has successfully imported.');
			}elseif ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {	// for excel
				try{
					// import data into the database
					$import = new CustomerOrderReportImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('customer-order-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('customer-order-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){					
					return redirect()->route('customer-order-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('customer-order-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('customerorders.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('customer-order-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv/txt file..!!');
			} 
		}
    }
	
	/**
     * Display the specified resource.
     *
     * @param  \App\CustomerOrder  $customerOrder
     * @return \Illuminate\Http\Response
     */
    public function trackShipment($id)
    {
		sleep(2);
		$track_numbers = array("references" => ["$id"]);
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.ypn.io/v2/shipping/track',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>json_encode($track_numbers),
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Basic TlRRPS4rbHNORytJdVRpMzZWOHpjT0JFLzd2N1Axc3luWFh5c0VKL3pTaE41M3ZjPTo=',
			'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		$responsedata = json_decode($response);
		if($http_status == 200){
			$track_status = $responsedata->trackers[0]->status ?? '';
			if(!empty($track_status)){
				// save this value on orderid and order item id
				DB::table('order_tracking')
				->where('shipper_tracking_id', $id)
				->update([
					'tracking_status' => $track_status->status,
					'tracking_message' => $track_status->message,
					'tracking_api_response' => $response,
					'api_response_code' => $http_status,
				]);
			}
		}else{
			// save this value on orderid and order item id
			DB::table('order_tracking')
			->where('shipper_tracking_id', $id)
			->update([
				'tracking_status' => 'failed',
				'tracking_message' => 'Failed',
				'tracking_api_response' => $response,
				'api_response_code' => $http_status,
			]);
		}
				
        $customerorders = CustomerOrder::select('customer_orders.*','customer_orders.order_id as cust_order_id','customer_orders.order_item_id as cust_order_item_id','order_tracking.*')
		->leftjoin('order_tracking', 'order_tracking.order_item_id', '=', 'customer_orders.order_item_id')
		->where('order_tracking.shipper_tracking_id', $id)
		->get();
		
		return view('customerorders.track-shipment',compact('customerorders','id'));
    }
}