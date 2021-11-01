<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Vendor;
use App\VendorStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\StockExport;
use App\Imports\StockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;

class VendorStockController extends Controller
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
		$search = $request->input('search');
		$stocks = VendorStock::where(function($query) use ($search) {
					$query->where('isbnno','LIKE','%'.$search.'%')	
						->orWhere('vendor_name','LIKE','%'.$search.'%')
						->orWhere('name','LIKE','%'.$search.'%')
						->orWhere('author','LIKE','%'.$search.'%')
						->orWhere('publisher','LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(stock_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
						->orWhere('binding_type','LIKE','%'.$search.'%')
						->orWhere('currency','LIKE','%'.$search.'%')
						->orWhere('price','LIKE','%'.$search.'%')
						->orWhere('discount','LIKE','%'.$search.'%')
						->orWhere('quantity','LIKE','%'.$search.'%');
				})->orderBy('vendor_name','ASC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $stocks->appends ( array (
			'search' => $search
		));
		
        return view('vendorstocks.index',compact('stocks','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('vendorstocks.create');
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
		$user = Auth::user();
		$uid = $user->id;
		
		// validation
        $request->validate([
			'stock_date' => 'required',
			'vendor_name' => 'required',
			'isbnno' => 'required',
			'name' => 'required',
			'author' => 'required',
			'publisher' => 'required',
			'binding_type' => 'required',
			'currency' => 'required',
			'price' => 'required',
			'discount' => 'required',
			'quantity' => 'required',
		]);
		
		// save value in db
		VendorStock::create([
			'stock_date' => $request->input('stock_date'),
			'vendor_name' => $request->input('vendor_name'),
			'isbnno' => $request->input('isbnno'),
			'name' => $request->input('name'),
			'author' => $request->input('author'),
			'publisher' => $request->input('publisher'),
			'binding_type' => $request->input('binding_type'),
			'currency' => $request->input('currency'),
			'price' => $request->input('price'),
			'discount' => $request->input('discount'),
			'quantity' => $request->input('quantity'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);
		
		return redirect()->route('vendorstocks.index')
                        ->with('success','Vendor stock added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = VendorStock::find($id);
		return view('vendorstocks.show',compact('stock'));
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
        $stock = VendorStock::find($id);
		return view('vendorstocks.edit',compact('stock'));
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
		$user = Auth::user();
		$uid = $user->id;
		
		// validation
        $request->validate([
			'stock_date' => 'required',
			'vendor_name' => 'required',
			'isbnno' => 'required',
			'name' => 'required',
			'author' => 'required',
			'publisher' => 'required',
			'binding_type' => 'required',
			'currency' => 'required',
			'price' => 'required',
			'discount' => 'required',
			'quantity' => 'required',
		]);
		
		// update value in db
		$stock = VendorStock::find($id);			
        $stock->stock_date = $request->input('stock_date');
        $stock->isbnno = $request->input('isbnno');
        $stock->vendor_name = $request->input('vendor_name');
        $stock->name = $request->input('name');
        $stock->author = $request->input('author');
        $stock->publisher = $request->input('publisher');
        $stock->binding_type = $request->input('binding_type');
        $stock->currency = $request->input('currency');
        $stock->price = $request->input('price');
        $stock->discount = $request->input('discount');
        $stock->quantity = $request->input('quantity');
        $stock->updated_by = $uid;
        $stock->save();
		
		return redirect()->route('vendorstocks.index')
                        ->with('success','Vendor stock updated successfully.');
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
		// remove image file if it exists
		$stock = VendorStock::find($id);
		// delete row
		DB::table("vendor_stocks")->where('id',$id)->delete();
        return redirect()->route('vendorstocks.index')
                        ->with('success','Vendor stock deleted successfully.');
    }
	
	/**
	* delete all stock.
	*
	* @return \Illuminate\Http\Response
	*/
    public function deleteVendorStockAll(Request $request)
    {
        $ids = $request->input('selectedval');
        DB::table("vendor_stocks")->whereIn('id',explode(",",$ids))->delete();
        return redirect()->route('vendorstocks.index')
                        ->with('success','Vendor stocks deleted successfully.');
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function stockImportExport()
    {
		return view('vendorstocks.import-export');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request) 
    {	
		$request->validate([
			'from_date' => ['required','date',new DateRange($request->input('from_date'),$request->input('to_date'))],
			'to_date' => 'required|date',
		]);
			
		return Excel::download(new StockExport($request), "stocks.".$request['exporttype']);
			
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
					'zipdir' => 'file|mimes:zip',
				],
				[
					'importfile.required' => 'Please select file to import.',
					'importfile.max' => 'Please upload upto 500MB file.'
				]
			);
		}else{ // for delete with new
			$this->validate($request,
				[
					'import_from_date' => ['required','date',new DateRange($request->input('import_from_date'),$request->input('import_to_date'))],
					'import_to_date' => 'required|date',
					'importfile' => 'required|max:512000',
					'zipdir' => 'file|mimes:zip',
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
					// import with delete old record and insert 
					if($request->input('importtype') == 'importwithupdate'){
						$stocks = DB::table("stocks")->whereBetween('stock_date',[$request->input('import_from_date'), $request->input('import_to_date')])->get();
						if(!empty( count($stocks) )){
							// remove selected item images
							foreach($stocks as $val){
								$imgurl = $val->image_url;
								if(file_exists( public_path($imgurl))) {
									unlink($imgurl);
								}
							}
						}
		
						// delete value between date and new update
						DB::table("stocks")->whereBetween('stock_date',[$request->input('import_from_date'), $request->input('import_to_date')])->delete();
					}
					
					$path = '';
					$filelist = '';
					// for unzip folder and extract images into the pload directory
					if($request->hasFile('zipdir')){
						
						$dirname = 'productimages';
						$year = date("Y");   
						$month = date("m");   
						$day = date("d");

						$path = $dirname.'/'.$year.'/'.$month.'/'.$day;
							
						$filename = $request->zipdir->getClientOriginalName();
						// upload image in define path
						$request->zipdir->move(public_path($path), $filename);
						
						// extract file
						$zip = Zip::open(public_path($path).'/'.$filename);
						$zip->extract(public_path() . "/$path");
						
						// delete zip file after extract
						$filelist = $zip->listFiles();
						
						// zip object close
						$zip->close();
						
						if(file_exists(public_path($path).'/'.$filename)) {
							// unlink zip file after extract
							unlink(public_path($path).'/'.$filename);
						}
					}
					
					// import data into the database
					$import = new StockImport($request, $path, $filelist);
					$path = $request->importfile->getRealPath();
		
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('stock-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('stock-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('stock-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('stock-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('stocks.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('stock-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}