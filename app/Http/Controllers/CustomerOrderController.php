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
		$this->middleware('permission:customer-order-import-export', ['only' => ['customer-order-import-export','customerorderimport','customerorderexport']]);
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
		
        //
		$customerorders = CustomerOrder::where(function($query) use ($search) {
					$query->Where('order_id','LIKE','%'.$search.'%')
					->orWhere('purchase_date','LIKE','%'.$search.'%')
					->orWhere('payments_date','LIKE','%'.$search.'%')
					->orWhere('reporting_date','LIKE','%'.$search.'%')
					->orWhere('sku','LIKE','%'.$search.'%')
					->orWhere('product_name','LIKE','%'.$search.'%')
					->orWhere('quantity_purchased','LIKE','%'.$search.'%')
					->orWhere('buyer_name','LIKE','%'.$search.'%')
					->orWhere('buyer_phone_number','LIKE','%'.$search.'%')
					->orWhere('order_item_id','LIKE','%'.$search.'%');
				})->orderBy('purchase_date','DESC')->paginate(10)->setPath('');
		
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
    public function show(CustomerOrder $customerOrder)
    {
        //
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
    public function destroy(CustomerOrder $customerOrder)
    {
        //
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
						$customerdata = CustomerOrder::where('order_id', '=', $val['order-id'])->get();
						if(empty($customerdata)){ // not inserted duplicated data
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
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}
