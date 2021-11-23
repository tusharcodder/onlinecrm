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
use App\Imports\VendorStockImport;
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
		$search = $request->input('search');
		$stocks = VendorStock::select('vendor_stocks.*','bindings.name as binding_type','currenciess.name as currency','vendors.name as vendor_name')
					->join("bindings","bindings.id","=","vendor_stocks.binding_id")
					->join("currenciess","currenciess.id","=","vendor_stocks.currency_id")
					->join("vendors","vendors.id","=","vendor_stocks.vendor_id")
					->where(function($query) use ($search) {
						$query->where('vendor_stocks.isbnno','LIKE','%'.$search.'%')	
						->orWhere('vendors.name','LIKE','%'.$search.'%')
						->orWhere('vendor_stocks.name','LIKE','%'.$search.'%')
						->orWhere('vendor_stocks.author','LIKE','%'.$search.'%')
						->orWhere('vendor_stocks.publisher','LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(vendor_stocks.stock_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
						->orWhere('bindings.name','LIKE','%'.$search.'%')
						->orWhere('currenciess.name','LIKE','%'.$search.'%')
						->orWhere('vendor_stocks.price','LIKE','%'.$search.'%')
						->orWhere('vendor_stocks.discount','LIKE','%'.$search.'%')
						->orWhere('vendor_stocks.quantity','LIKE','%'.$search.'%');
				})->orderBy('vendor_stocks.vendor_id','ASC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $stocks->appends ( array (
			'search' => $search
		));
		
        return view('reports.shipmentreport',compact('stocks','search'))
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
			
		return Excel::download(new VendorStockExport($request), "vendorstocks.".$request['exporttype']);
			
        //return Excel::download(new StockExport($request), "stocks.".$request['exporttype']);
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