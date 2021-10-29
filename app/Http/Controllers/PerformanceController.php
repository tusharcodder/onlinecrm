<?php

namespace App\Http\Controllers;

use File;
use Session;
use App\Performance;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use DateTime;
use App\Rules\DateRange; // date range rule validation
use DB;
use App\Exports\performanceExport;
use App\Imports\performanceImport;
use Maatwebsite\Excel\Facades\Excel;

class PerformanceController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:performances-list|performances-create|performances-edit|performances-delete|performances-import-export', ['only' => ['index','store']]);
         $this->middleware('permission:performances-list', ['only' => ['index']]);
         $this->middleware('permission:performances-create', ['only' => ['create','store']]);
         $this->middleware('permission:performances-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:performances-delete', ['only' => ['destroy', 'deleteperformancesall']]);
         $this->middleware('permission:performances-import-export', ['only' => ['performances-import-export','performancesimport','performancesexport']]);
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
		$performances = Performance::
					select('*')
					->where(function($query) use ($search) {	
						$query->Where('product_code','LIKE','%'.$search.'%')
						->orWhere('category','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $performances->appends ( array (
			'search' => $search
		));
		
        return view('performances.index',compact('performances','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$cat = ['Fast', 'Slow'];
		return view('performances.create',compact('cat'));
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
		[ 'rowitem.required' => 'Please add atlest one performance item into the list.']);
		
		$rowitem = json_decode($request->input('rowitem'));
		
		$data = [];
		foreach($rowitem as $val){
			
			// Check Duplicacy
			$data = DB::table("performances")->where('product_code',$val->pcode)->where('category',$val->category)->get();
			
			if( $data->count() != 0){
				
				// update value in db
				$performance = Performance::find($data[0]->id);
				$performance->product_code = $val->pcode;
				$performance->category = $val->category;
				$performance->sale_through = $val->sthrough;
				$performance->updated_by = $uid;
				$performance->save();
				
			}else{
				// save value in db
				$performance = Performance::create(['product_code' => $val->pcode,'category' => $val->category,'sale_through' => $val->sthrough,'created_by' => $uid,'updated_by' => $uid]);
			}
		}
		
		return redirect()->route('performances.index')
                        ->with('success','Performance added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Performance  $performance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$performance = Performance::select('*')->find($id);
		return view('performances.show',compact('performance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Performance  $performance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//
		$type = ['Fast', 'Slow'];
        $performance = Performance::find($id);
		return view('performances.edit',compact('type', 'performance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Performance  $performance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		//
		$user = Auth::user();
		$uid = $user->id;
		
		// validation
		$request->validate([
			'product_code' => 'required',
			'category' => 'required',
			'salethrough' => 'required|min:0|max:100',
		]);
		
		// Check Duplicacy
		$data = DB::table("performances")->where('product_code',$request->input('product_code'))->where('category',$request->input('category'))->where('category', '!=' ,$id)->get();
		
		if( $data->count() == 0){
			// update value in db
			$performance = Performance::find($id);
			$performance->product_code = $request->input('product_code');
			$performance->category = $request->input('category');
			$performance->sale_through = $request->input('salethrough');
			$performance->updated_by = $uid;
			$performance->save();
			
			return redirect()->route('performances.index')
							->with('success','Performance updated successfully.');
		}
		return redirect()->route('performances.index')->with('error','Performance already exist.');
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Performance  $performance
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		DB::table("performances")->where('id',$id)->delete();
        return redirect()->route('performances.index')
                        ->with('success','Performance deleted successfully.');
    }
	
	/**
	* delete all performance.
	*
	* @return \Illuminate\Http\Response
	*/
    public function deletePerformancesAll(Request $request)
    {
        $ids = $request->input('selectedval');
        DB::table("performances")->whereIn('id',explode(",",$ids))->delete();
        return redirect()->route('performances.index')
                        ->with('success','Performance deleted successfully.');
    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function performancesImportExport()
    {
		$category = ['Fast', 'Slow'];
		return view('performances.import-export', compact('category'));
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request) 
    {	
        return Excel::download(new PerformanceExport($request), "performances.".$request['exporttype']);
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
						DB::table("performances")->whereBetween('valid_from_date',[$request->input('import_from_date'), $request->input('import_to_date')])->delete();
					}
					// import data into the database
					$import = new PerformanceImport($request);
					$path = $request->importfile->getRealPath();
                    Excel::import($import, $request->importfile);
					
				}catch(\Exception $ex){
					return redirect()->route('performances-import-export')
                        ->with('error','Something wrong.');
				}catch(\InvalidArgumentException $ex){
					return redirect()->route('performances-import-export')
                        ->with('error','Wrong date format in some column.');
				}catch(\Error $ex){
					return redirect()->route('performances-import-export')
                        ->with('error','Something went wrong. check your file.');
				}

				if(empty($import->getRowCount())){
					return redirect()->route('performances-import-export')
                        ->with('error','No data found to imported.');
				}
               
				return redirect()->route('performances.index')
                        ->with('success','Your Data has successfully imported.');
			}else{
				return redirect()->route('performances-import-export')
                        ->with('error','File is a '.$extension.' file.!! Please upload a valid xls/xlsx/csv file..!!');
			} 
		}
    }
}
