<?php

namespace App\Http\Controllers;

use App\Common;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;

class CommonController extends Controller{
	//
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVendor($val)
    {
        $vendors = Vendor::where('type',$val)->pluck('vendor_name','id')->all();
        return json_encode($vendors);
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete($table, $column, Request $request)
    {
		$data = DB::table($table)->select($column)
                ->where($column,"LIKE","%{$request->search}%")
                ->groupBy($column)
                ->get();
		
		$response = array();
		foreach($data as $autocomplate){
			$response[] = array("value"=>$autocomplate->$column,"label"=>$autocomplate->$column);
		}
	  
        return response()->json($response);
        }

     public function getCurrentStock(Request $request){
        $search = $request->input('search');
		
        $stocks = DB::table('purchase_orders')
                                ->select('purchase_orders.isbn13',
                               'book_details.name as book_title',
                                DB::raw("case when sum(purchase_orders.quantity) is not null THEN sum(purchase_orders.quantity) else 0 end as purchase_quantity"),
                                DB::raw("case when sum(customer_orders.quantity_shipped) is not null then sum(customer_orders.quantity_shipped) else 0 end as sale_quantity"),
                                DB::raw("((case when sum(purchase_orders.quantity) is not null THEN sum(purchase_orders.quantity) else 0 END)-(case when sum(customer_orders.quantity_shipped) is not null then sum(customer_orders.quantity_shipped) else 0 END)) as stock"))
                                ->leftJoin('customer_orders','customer_orders.order_item_id','=','purchase_orders.isbn13')
                                ->leftJoin('book_details','book_details.isbnno','=','purchase_orders.isbn13')
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
}
?>