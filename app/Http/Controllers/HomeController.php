<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('permission:show-dashboard', ['only' => ['index']]);
        //$this->middleware(['auth','verified']);
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		// get low stock and out of stock item count		
		
        return view('home');
    }
	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function saleChartDetails()
    {
		// query for get sale chart details month wise of last 12 month
		$salecharts = Sale::
					select(DB::raw('sum(quantity) as totalqty'), DB::raw('MONTH(sale_date) as month'), DB::raw('MONTHNAME(sale_date) as monthname'), DB::raw('Year(sale_date) as year'),
					DB::raw("DATE_FORMAT(sale_date, '%b-%Y') as monyear"))
					->where('sale_date', '<=', DB::raw('NOW()'))
					->where('sale_date', '>=', DB::raw('Date_add(Now(),interval - 12 month)'))
					->groupBy('month','monyear')
					->orderBy('year')
					->orderBy('month')->get();
		
		return json_encode($salecharts);
    }
}
