<?php

namespace App\Http\Controllers;

use App\StockReminder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;

class StockReminderController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:stock-threshold', ['only' => ['create','store']]);
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//
		$thresholdval = StockReminder::first();
		$thresholdval = (!empty($thresholdval) ? $thresholdval->getAttributes() : $thresholdval);
		return view('stocks.threshold',compact('thresholdval'));
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
			'low_stock_threshold' => 'required|min:0|max:100',
			'out_of_stock_threshold' => 'required|min:0|max:100',
		]);
		
		// before edit truncate threshold table 
		StockReminder::truncate();
		
		// save value in db
		StockReminder::create([
			'low_stock_threshold' => $request->input('low_stock_threshold'),
			'out_of_stock_threshold' => $request->input('out_of_stock_threshold'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);

		return redirect()->route('stockthreshold.create')
                        ->with('success','Stock threshold added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockReminder  $stockReminder
     * @return \Illuminate\Http\Response
     */
    public function show(StockReminder $stockReminder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockReminder  $stockReminder
     * @return \Illuminate\Http\Response
     */
    public function edit(StockReminder $stockReminder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockReminder  $stockReminder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockReminder $stockReminder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockReminder  $stockReminder
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockReminder $stockReminder)
    {
        //
    }
}
