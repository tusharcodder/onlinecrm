<?php

namespace App\Http\Controllers;

use App\currencies;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\Emails; // multiple email rule validation
use DB;

class CurrenciesController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:currencies-list|currencies-create|currencies-edit|currencies-delete', ['only' => ['index','store']]);
		$this->middleware('permission:currencies-list', ['only' => ['index']]);
		$this->middleware('permission:currencies-create', ['only' => ['create','store']]);
		$this->middleware('permission:currencies-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:currencies-delete', ['only' => ['destroy']]);
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
		$currenciess = Currencies::where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%')
					->orWhere('symbol','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $currenciess->appends ( array (
			'search' => $search
		));
		
        return view('currencies.index',compact('currenciess','search'))
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
		return view('currencies.create');
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
			'name' => 'required|unique:currenciess,name',
		]);

		// save value in db
		$currencies = Currencies::create([
			'name' => $request->input('name'),
			'symbol' => $request->input('symbol'),
			'created_by' => $uid,
			'updated_by' => $uid
		]);
	
		return redirect()->route('currencies.index')
                        ->with('success','Currencies created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\currenciess  $currenciess
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$currencies = Currencies::find($id);
        return view('currencies.show',compact('currencies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\currenciess  $currenciess
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		$currencies = Currencies::find($id);
        return view('currencies.edit',compact('currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\currenciess  $currenciess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
		
        $request->validate([ // for validation
			'name' => 'required|unique:currenciess,name,'.$id,
		]);
		
		// update value in db
		$currencies = Currencies::find($id);
        $currencies->name = $request->input('name');
        $currencies->symbol = $request->input('symbol');
        $currencies->updated_by = $uid;
        $currencies->save();

		return redirect()->route('currencies.index')
                        ->with('success','Currencies updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\currenciess  $currenciess
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		DB::table("currenciess")->where('id',$id)->delete();
        return redirect()->route('currencies.index')
                        ->with('success','Currencies deleted successfully.');
    }
}
