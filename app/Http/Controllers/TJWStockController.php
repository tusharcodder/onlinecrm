<?php

namespace App\Http\Controllers; 

use File;
use Session;
use App\Vendor;
use App\Binding;
use App\Currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
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
		
        $stocks = DB::table('purchase_orders')
		->select('purchase_orders.isbn13','book_details.name as book_title',
		DB::raw("(sum(case when purchase_orders.quantity is not null THEN purchase_orders.quantity else 0 END)-(sum(case when customer_orders.quantity_to_be_shipped is not null then customer_orders.quantity_to_be_shipped else 0 END)+sum(case when order_tracking.quantity_shipped is not null then order_tracking.quantity_shipped else 0 END))) as stock"))
        ->join('skudetails','skudetails.isbn13','=','purchase_orders.isbn13')
        ->join('customer_orders','customer_orders.sku','=','skudetails.sku_code')		
		->leftJoin('book_details','book_details.isbnno','=','purchase_orders.isbn13')
        ->leftJoin('order_tracking','order_tracking.isbnno','=','purchase_orders.isbn13')
		->where(function($query) use ($search) {
			$query->where('purchase_orders.isbn13','LIKE','%'.$search.'%')						
			->orWhere('purchase_orders.book_title','LIKE','%'.$search.'%');
		})
		->groupby('purchase_orders.isbn13')  
		->orderBy('purchase_orders.book_title','ASC')->paginate(20)->setPath('');
        
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
}