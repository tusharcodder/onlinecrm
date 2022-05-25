<?php

namespace App\Http\Controllers;

use App\PriceInventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;
use App\Exports\PriceInventoryExport;
use App\Imports\PriceInventoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;
use File;

class PriceInventoryController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:price-inventory-import-export', ['only' => ['index']]);		
		$this->middleware('permission:download-price-inventory', ['only' => ['importexport']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //		
        return view('reports.priceinventory');
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
    public function destroy($id)
    {
		// 		
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function priceInventoryImportExport()
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
    public function importexport(Request $request) 
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
        // echo '<pre>';
        // print_r($orderdata);
        // echo '</pre>';
				// exit;
				// add data into order table
				if(!empty($orderdata)){
					DB::statement('truncate table price_inventory');
					foreach($orderdata as $key => $val){
            if(!empty($val['seller-sku']))
            {
                PriceInventory::create([
                  'sku' => $val['seller-sku'],                            
              ]);
            }	
					}
				}
        return Excel::download(new PriceInventoryExport($request), "PriceInventory.csv");
				return redirect()->route('import-export-price-list')
                        ->with('success','Your Data has successfully imported.');
			}elseif ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {	// for excel
				try{
					// import data into the database
					$import = new PriceInventoryImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
                    return Excel::download(new PriceInventoryExport($request), "PriceInventory.csv");
				}catch(\Exception $ex){
					return redirect()->route('import-export-price-list')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('import-export-price-list')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){					
					return redirect()->route('import-export-price-list')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('import-export-price-list')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('customerorders.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('import-export-price-list')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv/txt file..!!');
			} 
		}
    }

	
	
	
}
