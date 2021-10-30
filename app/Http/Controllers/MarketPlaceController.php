<?php

namespace App\Http\Controllers;

use App\MarketPlace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;

class MarketPlaceController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:market-place-list|market-place-create|market-place-edit|market-place-delete', ['only' => ['index','store']]);
		$this->middleware('permission:market-place-list', ['only' => ['index']]);
		$this->middleware('permission:market-place-create', ['only' => ['create','store']]);
		$this->middleware('permission:market-place-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:market-place-delete', ['only' => ['destroy']]);
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
        //
		$marketplaces = MarketPlace::where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%')
						->orWhere('number','LIKE','%'.$search.'%')	
						->orWhere('email','LIKE','%'.$search.'%')
						->orWhere('address','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $marketplaces->appends ( array (
			'search' => $search
		));
		
        return view('marketplaces.index',compact('marketplaces','search'))
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
		return view('marketplaces.create');
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

        //
		$request->validate([ // validation
			'name' => 'required|unique:market_places,name',
		]);

		// save value in db
		$marketPlace = MarketPlace::create([
			'name' => $request->input('name'),
			'number' => $request->input('number'),
			'email' => $request->input('email'),
			'address' => $request->input('address'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);
	
		return redirect()->route('marketplaces.index')
                        ->with('success','Market place created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MarketPlace  $marketPlace
     * @return \Illuminate\Http\Response
     */
    public function show(MarketPlace $marketPlace, $id)
    {
        //
		$marketplace = MarketPlace::find($id);
        return view('marketplaces.show',compact('marketplace'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarketPlace  $marketPlace
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketPlace $marketPlace, $id)
    {
        //
		$marketplace = MarketPlace::find($id);
        return view('marketplaces.edit',compact('marketplace'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarketPlace  $marketPlace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketPlace $marketPlace, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
		
        $request->validate([ // for validation
			'name' => 'required|unique:market_places,name,'.$id,
		]);
		
		// update value in db
		$marketplace = MarketPlace::find($id);
        $marketplace->name = $request->input('name');
        $marketplace->number = $request->input('number');
        $marketplace->email = $request->input('email');
        $marketplace->address = $request->input('address');
        $marketplace->updated_by = $uid;
        $marketplace->save();

		return redirect()->route('marketplaces.index')
                        ->with('success','Market place updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarketPlace  $marketPlace
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketPlace $marketPlace, $id)
    {
        //
		DB::table("market_places")->where('id',$id)->delete();
        return redirect()->route('marketplaces.index')
                        ->with('success','Market place deleted successfully.');
    }
}
