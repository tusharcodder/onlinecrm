<?php

namespace App\Http\Controllers; 

use File;
use Session;
use App\Vendor;
use App\Binding;
use App\Currencies;
use App\Imports\StockTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Exports\TjwStockExport;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\Common;

class TJWStockController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:tjw-stock-list', ['only' => ['index']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $search = $request->input('search');
		
        $stocks = DB::table('warehouse_stocks',)
		->select('warehouses.name','warehouse_stocks.isbn13','book_details.name as book_title',
		DB::raw("(sum(case when warehouse_stocks.quantity is not null THEN warehouse_stocks.quantity else 0 END)-(IFNULL( ( SELECT sum(customer_orders.quantity_to_be_shipped) from customer_orders INNER join skudetails on skudetails.sku_code = customer_orders.sku where skudetails.isbn13 = warehouse_stocks.isbn13 and customer_orders.warehouse_id = warehouse_stocks.warehouse_id), 0))) as stock"))
		->leftJoin('book_details','book_details.isbnno','=','warehouse_stocks.isbn13')
        ->leftJoin('warehouses','warehouses.id','=','warehouse_stocks.warehouse_id')       
		->where(function($query) use ($search) {
			$query->where('warehouse_stocks.isbn13','LIKE','%'.$search.'%')
			->orWhere('warehouses.name','LIKE','%'.$search.'%')
			->orWhere('book_details.name','LIKE','%'.$search.'%');
		})
		->groupby('warehouse_stocks.isbn13','warehouse_stocks.warehouse_id')  
		->orderBy('book_details.name','ASC')->paginate(20)->setPath('');
        
        // bind value with pagination link
        $pagination = $stocks->appends ( array (
			'search' => $search
        ));
       
		return view('stocks.index',compact('stocks','search'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
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
     * @param  \App\TJWStock  $TJWStock
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TJWStock  $TJWStock
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
     * @param  \App\TJWStock  $TJWStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TJWStock  $TJWStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 		//
    }
    public function view()
    {
        return view('stocks.stocktransfer');
    }
	
	//download excel
	public function export(Request $request) 
    {				
		return Excel::download(new TjwStockExport($request), "Current_Stock.".$request['exporttype']);	
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
					// import data into the database
					$import = new StockTransfer($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('stock-transfer')
                        ->with('error',$ex->getMessage());
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('stock-transfer')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('stock-transfer-import')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('stock-transfer')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('stock-transfer')
                        ->with('success','Your Data has successfully imported.','notsaveisbns');
			}else{
				return redirect()->route('stock-transfer-import')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}