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
		$stocks = VendorStock::select('stocks.*', 'product_images.image_url as img_url')
				->join('product_images', 'product_images.product_code', '=', 'stocks.product_code')
				->where(function($query) use ($search) {
					$query->where('stocks.manufacturer_name','LIKE','%'.$search.'%')	
						->orWhere('stocks.country','LIKE','%'.$search.'%')	
						->orWhere(DB::raw("DATE_FORMAT(stocks.manufacture_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')	
						->orWhere('stocks.cost','LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(stocks.stock_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
						->orWhere('stocks.brand','LIKE','%'.$search.'%')
						->orWhere('stocks.category','LIKE','%'.$search.'%')
						->orWhere('stocks.gender','LIKE','%'.$search.'%')
						->orWhere('stocks.colour','LIKE','%'.$search.'%')
						->orWhere('stocks.size','LIKE','%'.$search.'%')
						->orWhere('stocks.lotno','LIKE','%'.$search.'%')
						->orWhere('stocks.sku_code','LIKE','%'.$search.'%')
						->orWhere('stocks.product_code','LIKE','%'.$search.'%')
						->orWhere('stocks.hsn_code','LIKE','%'.$search.'%')
						->orWhere('stocks.online_mrp','LIKE','%'.$search.'%')
						->orWhere('stocks.offline_mrp','LIKE','%'.$search.'%')
						->orWhere('stocks.description','LIKE','%'.$search.'%')
						->orWhere('stocks.quantity','LIKE','%'.$search.'%');
				})->orderBy('stocks.id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $stocks->appends ( array (
			'search' => $search
		));
		
        return view('stocks.index',compact('stocks','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('stocks.create');
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
			'manufacturer_name' => 'required',
			'manufacture_date' => 'required',
			'stock_date' => 'required',
			'brand' => 'required',
			'category' => 'required',
			'size' => 'required',
			'lotno' => 'required',
			'sku_code' => 'required',
			'product_code' => 'required',
			'online_mrp' => 'required',
			'offline_mrp' => 'required',
			'quantity' => 'required',
			'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		
		$storefilepath = '';
		$path = '';
		if ($request->hasFile('product_image')) {
            //  Let's do everything here
            if ($request->file('product_image')->isValid()) {
				
				$dirname = 'productimages';
                $extension = $request->product_image->extension();
				$imagename = $request->product_image->getClientOriginalName();
				$filename = pathinfo($imagename, PATHINFO_FILENAME);
				$orgname = $filename.'_'.time().'.'.$extension; 
				
				$year = date("Y");   
				$month = date("m");   
				$day = date("d");

				$path = $dirname.'/'.$year.'/'.$month.'/'.$day;
						
				// upload image in define path
				$request->product_image->move(public_path($path), $orgname);
				$storefilepath = $path.'/'.$orgname;
            }
        }
		
		// save value in db
		Stock::create([
			'manufacturer_name' => $request->input('manufacturer_name'),
			'country' => $request->input('country'),
			'manufacture_date' => $request->input('manufacture_date'),
			'cost' => $request->input('cost'),
			'stock_date' => $request->input('stock_date'),
			'brand' => $request->input('brand'),
			'category' => $request->input('category'),
			'gender' => $request->input('gender'),
			'colour' => $request->input('colour'),
			'size' => $request->input('size'),
			'lotno' => $request->input('lotno'),
			'sku_code' => $request->input('sku_code'),
			'product_code' => $request->input('product_code'),
			'hsn_code' => $request->input('hsn_code'),
			'online_mrp' => $request->input('online_mrp'),
			'offline_mrp' => $request->input('offline_mrp'),
			'quantity' => $request->input('quantity'),
			'description' => $request->input('description'),
			'image_url' => $storefilepath,
			'created_by' => $uid,
			'updated_by' => $uid
		]);

		// call common method for adding product image
		$common = new Common();
		$common -> addProductImage($request->input('product_code'), $storefilepath);
		
		return redirect()->route('stocks.index')
                        ->with('success','Stock added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = VendorStock::select('stocks.*', 'product_images.image_url as img_url')
				->join('product_images', 'product_images.product_code', '=', 'stocks.product_code')->find($id);
		return view('stocks.show',compact('stock'));
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
        $stock = VendorStock::select('stocks.*', 'product_images.image_url as img_url')
				->join('product_images', 'product_images.product_code', '=', 'stocks.product_code')->find($id);
		return view('stocks.edit',compact('stock'));
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
			'manufacturer_name' => 'required',
			'manufacture_date' => 'required',
			'stock_date' => 'required',
			'brand' => 'required',
			'category' => 'required',
			'size' => 'required',
			'lotno' => 'required',
			'sku_code' => 'required',
			'product_code' => 'required',
			'online_mrp' => 'required',
			'offline_mrp' => 'required',
			'quantity' => 'required',
			'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		
		$storefilepath = '';
		$path = '';
		if ($request->hasFile('product_image')) {
            //  Let's do everything here
            if ($request->file('product_image')->isValid()) {
				
				$dirname = 'productimages';
                $extension = $request->product_image->extension();
				$imagename = $request->product_image->getClientOriginalName();
				$filename = pathinfo($imagename, PATHINFO_FILENAME);
				$orgname = $filename.'_'.time().'.'.$extension; 
				
				$year = date("Y");   
				$month = date("m");   
				$day = date("d");

				$path = $dirname.'/'.$year.'/'.$month.'/'.$day;
						
				// upload image in define path
				$request->product_image->move(public_path($path), $orgname);
				$storefilepath = $path.'/'.$orgname;
            }
        }
		
		// update value in db
		$stock = Stock::find($id);			
        $stock->manufacturer_name = $request->input('manufacturer_name');
        $stock->country = $request->input('country');
        $stock->manufacture_date = $request->input('manufacture_date');
        $stock->cost = $request->input('cost');
        $stock->stock_date = $request->input('stock_date');
        $stock->brand = $request->input('brand');
        $stock->category = $request->input('category');
        $stock->gender = $request->input('gender');
        $stock->colour = $request->input('colour');
        $stock->size = $request->input('size');
        $stock->lotno = $request->input('lotno');
        $stock->sku_code = $request->input('sku_code');
        $stock->product_code = $request->input('product_code');
        $stock->hsn_code = $request->input('hsn_code');
        $stock->online_mrp = $request->input('online_mrp');
        $stock->offline_mrp = $request->input('offline_mrp');
        $stock->quantity = $request->input('quantity');
        $stock->description = $request->input('description');
		if(!empty($storefilepath)){
			/* $imgurl = $stock->image_url;
			if(file_exists( public_path($imgurl)) && !empty($imgurl)) {
				unlink($imgurl);
			} */
			$stock->image_url = $storefilepath;
		}
        $stock->updated_by = $uid;
        $stock->save();
		
		// call common method for adding product image
		$common = new Common();
		$common -> addProductImage($request->input('product_code'), $storefilepath);
		
		return redirect()->route('stocks.index')
                        ->with('success','Stock updated successfully.');
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
		$imgurl = $stock->image_url;
		/* if(file_exists( public_path($imgurl)) && !empty($imgurl)) {
			unlink($imgurl);
		} */
		// delete row
		DB::table("stocks")->where('id',$id)->delete();
        return redirect()->route('stocks.index')
                        ->with('success','Stock deleted successfully.');
    }
	
	/**
	* delete all stock.
	*
	* @return \Illuminate\Http\Response
	*/
    public function deleteStockAll(Request $request)
    {
        $ids = $request->input('selectedval');
		$stocks = DB::table("stocks")->whereIn('id',explode(",",$ids))->get();
		if(!empty( count($stocks) )){
			// remove selected item images
			foreach($stocks as $val){
				$imgurl = $val->image_url;
				/* if(file_exists( public_path($imgurl)) && !empty($imgurl)) {
					unlink($imgurl);
				} */
			}
		}
        DB::table("stocks")->whereIn('id',explode(",",$ids))->delete();
        return redirect()->route('stocks.index')
                        ->with('success','Stock deleted successfully.');
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function stockImportExport()
    {
		return view('stocks.import-export');
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