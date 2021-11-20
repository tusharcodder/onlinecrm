<?php

namespace App\Http\Controllers;

use App\PurchaseOrder;
use App\Vendor;
use Illuminate\Http\Request;
use App\Rules\DateRange; // date range rule validation
use App\Exports\PurchaseOrderExport;
use App\Imports\PurchaseOrderImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use File;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:purchase-order-list', ['only' => ['index']]);		
		$this->middleware('permission:purchase-order-import-export', ['only' => ['purchase-order-import-export','purchaseorderimport','purchaseorderexport']]);
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
		
		$purchaseorders = PurchaseOrder::select('purchase_orders.*','book_details.name','vendors.name as vendor')
                    ->join('vendors','vendors.id','=','purchase_orders.vendor_id')
                    ->leftJoin('book_details','book_details.isbnno','=','purchase_orders.isbn13')
                    ->where(function($query) use ($search) {
					$query->Where('bill_no','LIKE','%'.$search.'%')
					->orWhere(DB::raw("DATE_FORMAT(purchase_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')			
					->orWhere('isbn13','LIKE','%'.$search.'%')
					->orWhere('book_title','LIKE','%'.$search.'%')
					->orWhere('quantity','LIKE','%'.$search.'%')
					->orWhere('mrp','LIKE','%'.$search.'%')
					->orWhere('discount','LIKE','%'.$search.'%')
                    ->orWhere('purchase_by','LIKE','%'.$search.'%')
                    ->orWhere('vendors.name','LIKE','%'.$search.'%')
					->orWhere('cost_price','LIKE','%'.$search.'%');
				})->orderBy('purchase_date','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $purchaseorders->appends ( array (
			'search' => $search
		));
		
        return view('purchaseorders.index',compact('purchaseorders','search'))
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
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchaseorders = PurchaseOrder::select('purchase_orders.*','book_details.name','vendors.name as vendor')
                            ->join('vendors','vendors.id','=','purchase_orders.vendor_id')
                            ->leftJoin('book_details','book_details.isbnno','=','purchase_orders.isbn13') 
                            ->where('purchase_orders.id',$id)
                            ->orderBy('purchase_date','DESC')->get();
         return view('purchaseorders.show',compact('purchaseorders'));                   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }

   	/**
    * @return \Illuminate\Support\Collection
    */
    public function purchaseImportExport()
    {
        //get vendor name
        $vendors = Vendor::get();
        return view('purchaseorders.import-export',compact('vendors'));

    }

       /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request) 
    {				
		return Excel::download(new PurchaseOrderExport($request), "Purchase.".$request['exporttype']);	
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
		}else{ // for delete with new
			$this->validate($request,
				[
					'import_from_date' => ['required','date',new DateRange($request->input('import_from_date'),$request->input('import_to_date'))],
					'import_to_date' => 'required|date',
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
						
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv"){
				try{
					// import with delete old record and insert 
					if($request->input('importtype') == 'importwithupdate'){
						// delete value between date and new update
						DB::table("purchase_orders")->whereBetween('purchase_date',[$request->input('import_from_date'), $request->input('import_to_date')])->delete();
					}
					// import data into the database
					$import = new PurchaseOrderImport($request);				
                    $path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('purchase-order-import-export')
                        ->with('error',$ex->getMessage());
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('purchase-order-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('purchase-order-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('purchase-order-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('purchase-order-import-export')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('purchase-order-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}
