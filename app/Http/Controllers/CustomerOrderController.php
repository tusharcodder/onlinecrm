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
						
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv" || $extension == "txt") {	
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
