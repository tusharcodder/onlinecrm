<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Discount;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\DiscountExport;
use App\Imports\DiscountImport;
use Maatwebsite\Excel\Facades\Excel;

class DiscountController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:discount-list|discount-create|discount-edit|discount-delete|discount-import-export', ['only' => ['index','store']]);
         $this->middleware('permission:discount-list', ['only' => ['index']]);
         $this->middleware('permission:discount-create', ['only' => ['create','store']]);
         $this->middleware('permission:discount-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:discount-delete', ['only' => ['destroy', 'deletediscountall']]);
         $this->middleware('permission:discount-import-export', ['only' => ['discount-import-export','discountimport','discountexport']]);
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
		$discounts = Discount::
					select('*',DB::raw("(SELECT stocks.image_url FROM stocks WHERE stocks.product_code = discounts.product_code GROUP BY stocks.product_code ORDER BY stocks.created_at DESC) as image_url"))
					->where(function($query) use ($search) {
					$query->where('vendor_type','LIKE','%'.$search.'%')	
						->orWhere('vendor_name','LIKE','%'.$search.'%')	
						->orWhere('aggregator_vendor_name','LIKE','%'.$search.'%')	
						->orWhere('product_code','LIKE','%'.$search.'%')
						->orWhere('discount','LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(valid_from_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%')
						->orWhere(DB::raw("DATE_FORMAT(valid_to_date,'%d-%m-%Y')"),'LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $discounts->appends ( array (
			'search' => $search
		));
		
        return view('discounts.index',compact('discounts','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$type = ['Aggregator', 'Online', 'SOR', 'Outride'];
		return view('discounts.create', compact('type'));
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
			'rowitem' => 'required',
		],
		[ 'rowitem.required' => 'Please add atlest one discount item into the list.']);
		
		$rowitem = json_decode($request->input('rowitem'));
		
		$data = [];
		foreach($rowitem as $val){
			// save value in db
			$discount = Discount::create(['vendor_type' => $val->type,'vendor_name' => $val->vendor,'aggregator_vendor_name' => $val->agvendor,'product_code' => $val->pcode,'discount' => $val->dis,'valid_from_date' => $val->vfdate,'valid_to_date' => $val->vtdate,'created_by' => $uid,'updated_by' => $uid]);
		}
		
		return redirect()->route('discounts.index')
                        ->with('success','Discount added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$discount = Discount::
					select('*',DB::raw("(SELECT stocks.image_url FROM stocks WHERE stocks.product_code = discounts.product_code GROUP BY stocks.product_code ORDER BY stocks.created_at DESC) as image_url"))
					->find($id);
		return view('discounts.show',compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//
		$type = ['Aggregator', 'Online', 'SOR', 'Outride'];
        $discount = Discount::find($id);
		return view('discounts.edit',compact('type', 'discount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		//
		$user = Auth::user();
		$uid = $user->id;
		
		// validation
		if($request->input('type') != "Aggregator"){ // validation not for Aggregator
			$request->validate([
				'type' => 'required',
				'vendor' => 'required',
				'product_code' => 'required',
				'discount' => 'required|min:0|max:100',
				'valid_from_date' => ['required','date',new DateRange($request->input('valid_from_date'),$request->input('valid_to_date'))],
				'valid_to_date' => 'required|date',
			]);
		}else{ // validation for Aggregator
			$request->validate([
				'type' => 'required',
				'vendor' => 'required',
				'aggregator_vendor' => 'required',
				'product_code' => 'required',
				'discount' => 'required|min:0|max:100',
				'valid_from_date' => ['required','date',new DateRange($request->input('valid_from_date'),$request->input('valid_to_date'))],
				'valid_to_date' => 'required|date',
			]);
		}
		
		// update value in db
		$discount = Discount::find($id);
        $discount->vendor_type = $request->input('type');
        $discount->vendor_name = $request->input('vendor');
        $discount->aggregator_vendor_name = !empty($request->input('aggregator_vendor')) ? $request->input('aggregator_vendor') :"";
        $discount->product_code = $request->input('product_code');
        $discount->discount = $request->input('discount');
        $discount->valid_from_date = $request->input('valid_from_date');
        $discount->valid_to_date = $request->input('valid_to_date');
        $discount->updated_by = $uid;
        $discount->save();
		
		return redirect()->route('discounts.index')
                        ->with('success','Discount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		DB::table("discounts")->where('id',$id)->delete();
        return redirect()->route('discounts.index')
                        ->with('success','Discount deleted successfully.');
    }
	
	/**
	* delete all discount.
	*
	* @return \Illuminate\Http\Response
	*/
    public function deleteDiscountAll(Request $request)
    {
        $ids = $request->input('selectedval');
        DB::table("discounts")->whereIn('id',explode(",",$ids))->delete();
        return redirect()->route('discounts.index')
                        ->with('success','Discount deleted successfully.');
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function discountImportExport()
    {
		$type = ['Aggregator', 'Online', 'SOR', 'Outride'];
		return view('discounts.import-export', compact('type'));
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
			
        return Excel::download(new DiscountExport($request), "discounts.".$request['exporttype']);
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
						
			if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
				try{
					// import with delete old record and insert 
					if($request->input('importtype') == 'importwithupdate'){
						// delete value between date and new update
						DB::table("discounts")->whereBetween('valid_from_date',[$request->input('import_from_date'), $request->input('import_to_date')])->delete();
					}
					// import data into the database
					$import = new DiscountImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
				}catch(\Exception $ex){
					return redirect()->route('discount-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('discount-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('discount-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('discount-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('discounts.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('discount-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}
