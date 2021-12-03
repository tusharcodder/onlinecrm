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
use App\Exports\PurchaseReportExport;
use App\Imports\VendorStockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;
use App\Support\Collection;

class PurchaseReportController extends Controller
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
		$purchaseorders = [];
		$result = DB::table('purchase_orders')
		->join('customer_orders','order_item_id','purchase_orders.isbn13')
		->leftjoin('book_details','book_details.isbnno','purchase_orders.isbn13')
		->select('purchase_orders.isbn13','book_details.name',
		DB::raw("(sum(customer_orders.quantity_purchased) - sum(purchase_orders.quantity)) as quantity")
		)->groupby('purchase_orders.isbn13')->get();

		if($result->count() > 0){
			foreach($result as $value){
				if($value->quantity > 0){
					$flag = 0;
					$remainquantity = $value->quantity;
					//check priority wise						
					for($i=1; $i<10; $i++){
						$venderdetails = DB::table('vendor_stocks')
						->join('vendors','vendors.id','vendor_stocks.vendor_id')->select('vendors.name','author','publisher','quantity')
						->where('vendors.priority',$i)->where('vendor_stocks.isbnno',$value->isbn13)
						->get();
						if($venderdetails->count() > 0){
							//check vendor have quantity more then need
							if($venderdetails[0]->quantity >= $remainquantity){
								$flag = 1;
								$dataarray = array(
									"isbn13"=>$value->isbn13,
									'book'=>$value->name,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'quantity'=>$remainquantity,
									'vendor_name'=>$venderdetails[0]->name,
								);
								$purchaseorders[] =$dataarray;
							}
							else {
								// get remain quantity and check in other vendor stock
								$remainquantity = ($value->quantity - $venderdetails[0]->quantity); 
								$dataarray = array(
									"isbn13"=>$value->isbn13,
									'book'=>$value->name,
									'author'=>$venderdetails[0]->author,
									'publisher'=>$venderdetails[0]->publisher,
									'quantity'=> $venderdetails[0]->quantity,
									'vendor_name'=>$venderdetails[0]->name,
								);
								$purchaseorders[] =$dataarray;
							}
								
						}
						if($flag > 0)
							break;
					}

				}
					
				
			}         
		}
		$purchaseorders = (new Collection($purchaseorders))->sortBy('book')->paginate(10)->setPath('');
		return view('reports.purchasereport',compact('purchaseorders'))
		->with('i', ($request->input('page', 1) - 1) * 10);

	}	

	//download excel
	public function export(Request $request) 
    {				
		return Excel::download(new PurchaseReportExport($request), "PurchaseReport.".$request['exporttype']);	
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		// get vendor, binding and currency list
		$vendor = Vendor::get();
		$currency = Currencies::get();
		$binding = Binding::get();
		return view('vendorstocks.create',compact('vendor','currency','binding'));
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
			'vendor_id' => $request->input('vendor_name'),
			'isbnno' => $request->input('isbnno'),
			'name' => $request->input('name'),
			'author' => $request->input('author'),
			'publisher' => $request->input('publisher'),
			'binding_id' => $request->input('binding_type'),
			'currency_id' => $request->input('currency'),
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
        $stock = VendorStock::select('vendor_stocks.*','bindings.name as binding_type','currenciess.name as currency','vendors.name as vendor_name')
					->join("bindings","bindings.id","=","vendor_stocks.binding_id")
					->join("currenciess","currenciess.id","=","vendor_stocks.currency_id")
					->join("vendors","vendors.id","=","vendor_stocks.vendor_id")->find($id);
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
		// get vendor, binding and currency list
		$vendor = Vendor::get();
		$currency = Currencies::get();
		$binding = Binding::get();
		return view('vendorstocks.edit',compact('stock','vendor','currency','binding'));
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
        $stock->vendor_id = $request->input('vendor_name');
        $stock->name = $request->input('name');
        $stock->author = $request->input('author');
        $stock->publisher = $request->input('publisher');
        $stock->binding_id = $request->input('binding_type');
        $stock->currency_id = $request->input('currency');
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
		$vendor = Vendor::get();
		$currency = Currencies::get();
		$binding = Binding::get();
		return view('vendorstocks.import-export',compact('vendor','currency','binding'));
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