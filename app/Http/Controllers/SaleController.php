<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Sale;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\SaleExport;
use App\Imports\SaleImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;

class SaleController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:sale-list|sale-create|sale-edit|sale-delete|sale-import-export', ['only' => ['index','store']]);
         $this->middleware('permission:sale-list', ['only' => ['index']]);
         $this->middleware('permission:sale-create', ['only' => ['create','store']]);
         $this->middleware('permission:sale-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:sale-delete', ['only' => ['destroy', 'deletestockall']]);
         $this->middleware('permission:sale-import-export', ['only' => ['sale-import-export','stockimport','stockexport']]);
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
		$sales = Sale::
					select('sales.*', 'product_images.image_url as img_url')
					->join('product_images', 'product_images.product_code', '=', 'sales.product_code')
					->where(function($query) use ($search) {
					$query->where('sales.invoice_no','LIKE','%'.$search.'%')	
						->orWhere('sales.po_no','LIKE','%'.$search.'%')		
						->orWhere('sales.brand','LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(sales.sale_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
						->orWhere('sales.category','LIKE','%'.$search.'%')
						->orWhere('sales.vendor_type','LIKE','%'.$search.'%')
						->orWhere('sales.vendor_name','LIKE','%'.$search.'%')
						->orWhere('sales.aggregator_vendor_name','LIKE','%'.$search.'%')
						->orWhere('sales.colour','LIKE','%'.$search.'%')
						->orWhere('sales.size','LIKE','%'.$search.'%')
						->orWhere('sales.sku_code','LIKE','%'.$search.'%')
						->orWhere('sales.product_code','LIKE','%'.$search.'%')
						->orWhere('sales.hsn_code','LIKE','%'.$search.'%')
						->orWhere('sales.mrp','LIKE','%'.$search.'%')
						->orWhere('sales.state','LIKE','%'.$search.'%')
						->orWhere('sales.sale_price','LIKE','%'.$search.'%')
						->orWhere('sales.cost_price','LIKE','%'.$search.'%')
						->orWhere('sales.quantity','LIKE','%'.$search.'%');
				})->orderBy('sales.id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $sales->appends ( array (
			'search' => $search
		));
		
        return view('sales.index',compact('sales','search'))
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
		return view('sales.create');
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
		
		if($request->input('vendor_type') != 'Aggregator'){ // not for Aggregator vendor validation
			// validation
			$request->validate([
				'sale_date' => 'required',
				'invoice_no' => 'required',
				'po_no' => 'required',
				'vendor_type' => 'required',
				'vendor_name' => 'required',
				'brand' => 'required',
				'category' => 'required',
				'size' => 'required',
				'sku_code' => 'required',
				'product_code' => 'required',
				'mrp' => 'required',
				'before_tax_amount' => 'required',
				'state' => 'required',
				'receivable_amount' => 'required',
				'quantity' => 'required',
			]);
		}else{
			// validation
			$request->validate([
				'sale_date' => 'required',
				'invoice_no' => 'required',
				'po_no' => 'required',
				'vendor_type' => 'required',
				'vendor_name' => 'required',
				'aggregator_vendor_name' => 'required',
				'brand' => 'required',
				'category' => 'required',
				'size' => 'required',
				'sku_code' => 'required',
				'product_code' => 'required',
				'mrp' => 'required',
				'before_tax_amount' => 'required',
				'state' => 'required',
				'receivable_amount' => 'required',
				'quantity' => 'required',
			]);
		}
		
		// save value in db
		Sale::create([
			'sale_date' => $request->input('sale_date'),
			'invoice_no' => $request->input('invoice_no'),
			'po_no' => $request->input('po_no'),
			'brand' => $request->input('brand'),
			'category' => $request->input('category'),
			'vendor_type' => $request->input('vendor_type'),
			'vendor_name' => $request->input('vendor_name'),
			'aggregator_vendor_name' => $request->input('aggregator_vendor_name'),
			'hsn_code' => $request->input('hsn_code'),
			'sku_code' => $request->input('sku_code'),
			'product_code' => $request->input('product_code'),
			'colour' => $request->input('colour'),
			'size' => $request->input('size'),
			'quantity' => $request->input('quantity'),
			'mrp' => $request->input('mrp'),
			'before_tax_amount' => $request->input('before_tax_amount'),
			'state' => $request->input('state'),
			'cgst' => $request->input('cgst'),
			'sgst' => $request->input('sgst'),
			'igst' => $request->input('igst'),
			'vendor_discount' => $request->input('vendiscount'),
			'sale_price' => $request->input('sale_price'),
			'total_sale_amount' => $request->input('total_sale_amount'),
			'cost_price' => $request->input('cost_price'),
			'total_cost_amount' => $request->input('total_cost_amount'),
			'receivable_amount' => $request->input('receivable_amount'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);

		return redirect()->route('sales.index')
                        ->with('success','Sale added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::
				select('*',DB::raw("(SELECT stocks.image_url FROM stocks WHERE stocks.product_code = sales.product_code GROUP BY stocks.product_code ORDER BY stocks.created_at DESC) as image_url"))
				->find($id);
		return view('sales.show',compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//
        $sale = Sale::find($id);
		return view('sales.edit',compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
		
		// validation
        if($request->input('vendor_type') != 'Aggregator'){ // not for Aggregator vendor validation
			// validation
			$request->validate([
				'sale_date' => 'required',
				'invoice_no' => 'required',
				'po_no' => 'required',
				'vendor_type' => 'required',
				'vendor_name' => 'required',
				'brand' => 'required',
				'category' => 'required',
				'size' => 'required',
				'sku_code' => 'required',
				'product_code' => 'required',
				'mrp' => 'required',
				'before_tax_amount' => 'required',
				'state' => 'required',
				'receivable_amount' => 'required',
				'quantity' => 'required',
			]);
		}else{
			// validation
			$request->validate([
				'sale_date' => 'required',
				'invoice_no' => 'required',
				'po_no' => 'required',
				'vendor_type' => 'required',
				'vendor_name' => 'required',
				'aggregator_vendor_name' => 'required',
				'brand' => 'required',
				'category' => 'required',
				'size' => 'required',
				'sku_code' => 'required',
				'product_code' => 'required',
				'mrp' => 'required',
				'before_tax_amount' => 'required',
				'state' => 'required',
				'receivable_amount' => 'required',
				'quantity' => 'required',
			]);
		}
		
		// update value in db
		$sale = Sale::find($id);			
        $sale->sale_date = $request->input('sale_date');
        $sale->invoice_no = $request->input('invoice_no');
        $sale->po_no = $request->input('po_no');
        $sale->brand = $request->input('brand');
        $sale->category = $request->input('category');
        $sale->vendor_type = $request->input('vendor_type');
        $sale->vendor_name = $request->input('vendor_name');
        $sale->aggregator_vendor_name = $request->input('aggregator_vendor_name');
        $sale->hsn_code = $request->input('hsn_code');
        $sale->sku_code = $request->input('sku_code');
        $sale->product_code = $request->input('product_code');
        $sale->colour = $request->input('colour');
        $sale->size = $request->input('size');
        $sale->quantity = $request->input('quantity');
        $sale->mrp = $request->input('mrp');
        $sale->before_tax_amount = $request->input('before_tax_amount');
        $sale->state = $request->input('state');
        $sale->cgst = $request->input('cgst');
        $sale->sgst = $request->input('sgst');
        $sale->igst = $request->input('igst');
		$sale->vendor_discount = $request->input('vendiscount');
        $sale->sale_price = $request->input('sale_price');
        $sale->total_sale_amount = $request->input('total_sale_amount');
        $sale->cost_price = $request->input('cost_price');
        $sale->total_cost_amount = $request->input('total_cost_amount');
        $sale->receivable_amount = $request->input('receivable_amount');
        $sale->updated_by = $uid;
        $sale->save();
		
		return redirect()->route('sales.index')
                        ->with('success','Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete row
		DB::table("sales")->where('id',$id)->delete();
        return redirect()->route('sales.index')
                        ->with('success','Sale deleted successfully.');
    }
	
	/**
	* delete all sale.
	*
	* @return \Illuminate\Http\Response
	*/
    public function deleteSaleAll(Request $request)
    {
        $ids = $request->input('selectedval');
        DB::table("sales")->whereIn('id',explode(",",$ids))->delete();
        return redirect()->route('sales.index')
                        ->with('success','Sale deleted successfully.');
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function saleImportExport()
    {
		return view('sales.import-export');
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
			
		return Excel::download(new SaleExport($request), "sales.".$request['exporttype']);
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
						DB::table("sales")->whereBetween('sale_date',[$request->input('import_from_date'), $request->input('import_to_date')])->delete();
					}
					// import data into the database
					$import = new SaleImport($request);
					$path = $request->importfile->getRealPath();

                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('sale-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('sale-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('sale-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('sale-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('sales.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('sale-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}
