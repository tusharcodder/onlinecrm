<?php

namespace App\Http\Controllers;

use App\SkuDetail;
use App\MarketPlace;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SKUDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
		$skudetails = SkuDetail::select('skudetails.*','market_places.name as mplace','warehouses.name as warehouse')
        ->join('market_places','market_places.id','=','skudetails.market_id')
        ->join('warehouses','warehouses.id','=','skudetails.warehouse_id')
        ->where(function($query) use ($search) {
					$query->where('market_places.name','LIKE','%'.$search.'%')						
						->orWhere('warehouses.name','LIKE','%'.$search.'%')
						->orWhere('isbn13','LIKE','%'.$search.'%')
						->orWhere('isbn10','LIKE','%'.$search.'%');
				})->orderBy('skudetails.id','desc')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $skudetails->appends ( array (
			'search' => $search
		));
		
        return view('skudetails.index',compact('skudetails','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get market place records
        $marketplaces=MarketPlace::get();
        $warehouses=Warehouse::get();
        return view('skudetails.create',compact('marketplaces','warehouses'));
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
		
        //check validation
		$request->validate([
			'mplace' => 'required',			
			'warehouse' => 'required',	
            'sku' => 'required|unique:skudetails,sku_code',	
            'isbn13' => 'required',	
            'isbn10' => 'required',		
            'mrp' => 'required',		
		]);
		
		// save value in db
		$skudetails = SkuDetail::create([
								'market_id' => $request->input('mplace'),
								'warehouse_id' => $request->input('warehouse'),
								'isbn13' => $request->input('isbn13'),
								'isbn10' => $request->input('isbn10'),
								'mrp' => $request->input('mrp'),
                                'sku_code' => $request->input('sku'),
                                'disc' => $request->input('disc'),
                                'wght' => $request->input('wght'),
                                'pkg_wght' => $request->input('pgkwght'),
								'created_by' => $uid,
								'updated_by' => $uid
							]);
				
		return redirect()->route('skudetails.index')
                        ->with('success','Sku Detail created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SkuDetail  $skuDetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
      $skudetail =  SkuDetail::select('skudetails.*','market_places.name as mplace','warehouses.name as warehouse')
                                ->join('market_places','market_places.id','=','skudetails.market_id')
                                ->join('warehouses','warehouses.id','=','skudetails.warehouse_id')
                                ->where('skudetails.id',$id)->get();

        return view('skudetails.show',compact('skudetail'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SkuDetail  $skuDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $marketplaces=MarketPlace::get();       
        $warehouses=Warehouse::get();
        $skudetails = SkuDetail::find($id);
        // echo '<pre>';
        // print_r($skudetails)       ;
        // echo '</pre>';
        // exit;
        return view('skudetails.edit',compact('skudetails','warehouses','marketplaces'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SkuDetail  $skuDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $user = Auth::user();
		$uid = $user->id;
		
        //check validation
		$request->validate([
			'mplace' => 'required',			
			'warehouse' => 'required',	
            'sku' => 'required|unique:skudetails,sku_code,'.$id,	
            'isbn13' => 'required',	
            'isbn10' => 'required',		
            'mrp' => 'required',		
		]);		
			
		// update value in db
		$skudetail = SkuDetail::find($id);       
        $skudetail->market_id  = $request->input('mplace');      
        $skudetail->warehouse_id  = $request->input('warehouse');
        $skudetail->isbn13 = $request->input('isbn13');  
		$skudetail->isbn10 = $request->input('isbn10');        
		$skudetail->sku_code = $request->input('sku');  
        $skudetail->mrp  = $request->input('mrp');
        $skudetail->disc = $request->input('disc');  
		$skudetail->wght = $request->input('wght');        
		$skudetail->pkg_wght = $request->input('pgkwght');       
        $skudetail->updated_by = $uid;
        $skudetail->save();
		return redirect()->route('skudetails.index')
                        ->with('success','Sku Detail updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SkuDetail  $skuDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //record deleted by id
        DB::table("skudetails")->where('id',$id)->delete();
        return redirect()->route('skudetails.index')
                        ->with('success','Sku Detail deleted successfully.');
    }
}
