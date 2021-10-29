<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Gstslab;
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

class GstslabController extends Controller
{
		/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:gstslab-list|gstslab-create|gstslab-edit|gstslab-delete', ['only' => ['index','store']]);
         $this->middleware('permission:gstslab-list', ['only' => ['index']]);
         $this->middleware('permission:gstslab-create', ['only' => ['create','store']]);
         $this->middleware('permission:gstslab-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:gstslab-delete', ['only' => ['destroy']]);
         
    }
	
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$search = $request->input('search');
        //
		$gstslb = Gstslab::where(function($query) use ($search) {
					$query->where('amount_from','LIKE','%'.$search.'%')
						->orWhere('amount_to','LIKE','%'.$search.'%')
						->orWhere('gst_per','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $gstslb->appends ( array (
			'search' => $search
		));
		
        return view('gstslab.index',compact('gstslb','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        		
        return view('gstslab.create');
    }
	
	 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$user = Auth::user();
		$uid = $user->id;
        //
		$this->validate($request, [
            'amountfrom' => 'required|numeric',
            'amountto' => 'required|numeric',
			'gstper' => 'required|numeric',
        ]);


        $Gstslb = gstslab::create([
			'amount_from' => $request->input('amountfrom'),
			'amount_to' => $request->input('amountto'),
			'gst_per' => $request->input('gstper'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);
       


        return redirect()->route('gstslab.index')
                        ->with('success','GST Slabs created successfully.');
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
        $gstslb = gstslab::find($id);
		return view('gstslab.edit',compact('gstslb'));
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
		$this->validate($request, [
            'amountfrom' => 'required|numeric',
            'amountto' => 'required|numeric',
			'gstper' => 'required|numeric',
        ]);
		
		
		// update value in db
		$Gstslb = gstslab::find($id);			
        $Gstslb->amount_from = $request->input('amountfrom');
        $Gstslb->amount_to = $request->input('amountto');
        $Gstslb->gst_per = $request->input('gstper');        
        $Gstslb->updated_by = $uid;
        $Gstslb->save();
		
		return redirect()->route('gstslab.index')
                        ->with('success','GST Slabs updated successfully.');
    }
	
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gstslb = gstslab::
				select('*')
				->find($id);
		return view('gstslab.show',compact('gstslb'));
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
		DB::table("gstslabs")->where('id',$id)->delete();
        return redirect()->route('gstslab.index')
                        ->with('success','GST Slabs deleted successfully.');
    }
}