<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Sale;
use App\Stock;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\StockExport;
use App\Exports\StockOutExport;
use App\Exports\StockLowExport;
use App\Imports\StockImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Zip;
use App\StockReminder;

class BCSaleController extends Controller
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
					select('*',DB::raw("(SELECT stocks.image_url FROM stocks WHERE stocks.product_code = sales.product_code GROUP BY stocks.product_code ORDER BY stocks.created_at DESC) as image_url"))
					->where(function($query) use ($search) {
					$query->where('invoice_no','LIKE','%'.$search.'%')	
						->orWhere('po_no','LIKE','%'.$search.'%')		
						->orWhere('brand','LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(sale_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
						->orWhere('category','LIKE','%'.$search.'%')
						->orWhere('vendor_type','LIKE','%'.$search.'%')
						->orWhere('vendor_name','LIKE','%'.$search.'%')
						->orWhere('aggregator_vendor_name','LIKE','%'.$search.'%')
						->orWhere('colour','LIKE','%'.$search.'%')
						->orWhere('size','LIKE','%'.$search.'%')
						->orWhere('sku_code','LIKE','%'.$search.'%')
						->orWhere('product_code','LIKE','%'.$search.'%')
						->orWhere('hsn_code','LIKE','%'.$search.'%')
						->orWhere('mrp','LIKE','%'.$search.'%')
						->orWhere('state','LIKE','%'.$search.'%')
						->orWhere('sale_price','LIKE','%'.$search.'%')
						->orWhere('cost_price','LIKE','%'.$search.'%')
						->orWhere('quantity','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $sales->appends ( array (
			'search' => $search
		));
		
        return view('bcsales.index',compact('sales','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
	
	public function getSale( Request $request )
    {
		$skuval = $request->input('skuval');
		
		$saledata = DB::table('sales')->where('sku_code', $skuval)->get();
		
		$results = array(
			"sEcho" => 1,
			"iTotalRecords" => (!empty($saledata)? count($saledata) :0),
			"iTotalDisplayRecords" => (!empty($saledata)? count($saledata) :0),
			"aaData" => $saledata
		);
		
		echo json_encode($results);
		/*
		$sale = Sale::find( $saledata[0]->id );
		return view('bcsales.edit',compact('stock'));
		*/
    }
	
	public function saveSale( Request $request )
    {
		//
		$user = Auth::user();
		$uid = $user->id;
		
		/*if($request->input('vendor_type') != 'Aggregator'){ // not for Aggregator vendor validation
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
		}*/
		
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
			'sale_price' => $request->input('sale_price'),
			'total_sale_amount' => $request->input('total_sale_amount'),
			'cost_price' => $request->input('cost_price'),
			'total_cost_amount' => $request->input('total_cost_amount'),
			'receivable_amount' => $request->input('receivable_amount'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);
		return 1;
    }

    public function deleteBCSale( Request $request )
    {
		//
		// remove image file if it exists
		$id = $request->input('id');
		/*$stock = Stock::find($id);
		$imgurl = $stock->image_url;
		if(file_exists( public_path($imgurl))) {
			unlink($imgurl);
		}*/
		// delete row
		DB::table("sales")->where('id',$id)->delete();
        return 1;
    }
	
}
