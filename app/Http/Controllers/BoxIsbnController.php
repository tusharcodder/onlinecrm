<?php

namespace App\Http\Controllers;

use App\Boxisbn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class BoxIsbnController extends Controller
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
		$boxisbns = Boxisbn::where('box_isbn13','LIKE','%'.$search.'%')
                    ->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $boxisbns->appends ( array (
			'search' => $search
		));

        $isbn13 = DB::table('skudetails')->select(DB::raw("DISTINCT isbn13"))->get();
		
        return view('boxisbns.index',compact('boxisbns','search', 'isbn13'))
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
			'name' => 'required|unique:box_parent_isbns,box_isbn13',
            'bookisbnsto' => 'required',
		],[
            'name.required'=>'Box Isbn is required'
        ]);
        //save box isbn in parent table
       $boxisbn =  Boxisbn::create([
            'box_isbn13' => $request->input('name'),               
            'created_by' => $uid,
            'updated_by' => $uid
        ]);
        $boxisbn_id =  $boxisbn->id;
        $book_isbns = $request->input('bookisbnsto');
        foreach($book_isbns as $isbn){
            // save value in db
            DB::table('box_child_isbns')->insert([
                'box_isbn_id' => $boxisbn_id,
                'book_isbn13' =>$isbn,
                'created_by' => $uid,
                'updated_by' => $uid
            ]);
        }
		
	
		return redirect()->route('boxisbns.index')
                        ->with('success','Box Isbn created successfully.');
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
		$boxparentisbn = Boxisbn::find($id);
        $boxchildisbn = DB::table('box_child_isbns')
                        ->select('book_isbn13')
                        ->where('box_isbn_id', $id)
                        ->get();
        
        $isbn13 = DB::table('skudetails')->select(DB::raw("DISTINCT isbn13"))->get();

        $boxisbns = Boxisbn::orderBy('id','DESC')->paginate(10)->setPath('');
		$search = '';
		// bind value with pagination link
		

        return view('boxisbns.edit',compact('boxparentisbn', 'boxchildisbn', 'isbn13', 'boxisbns', 'search'));
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
			'name' => 'required|unique:box_parent_isbns,box_isbn13,'.$id,
            'book_isbn_to' => 'required',
		]);
        //$book_isbns = $request->input('book_isbns');
        //print_r( $book_isbns);exit;
	   //save box isbn in parent table
       $boxisbn =  Boxisbn::find($id);
       $boxisbn->box_isbn13 = $request->input('name');               
       $boxisbn->created_by = $uid;
       $boxisbn->updated_by = $uid;
       $boxisbn->save();
       
        $boxisbn_id =  $id;
        //delete box_child_isbn 
        DB::table('box_child_isbns')->where('box_isbn_id', $id)->delete();

        $book_isbns = $request->input('book_isbn_to');
       
        foreach($book_isbns as $isbn){
            // save value in db
            DB::table('box_child_isbns')->insert([
                'box_isbn_id' => $boxisbn_id,
                'book_isbn13' =>$isbn,
                'created_by' => $uid,
                'updated_by' => $uid
            ]);
        }

		return redirect()->route('boxisbns.index')
                        ->with('success','Box Isbn updated successfully.');
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
		DB::table("box_parent_isbns")->where('id',$id)->delete();
        DB::table("box_child_isbns")->where('box_isbn_id',$id)->delete();
        return redirect()->route('currencies.index')
                        ->with('success','Currencies deleted successfully.');
    }
    
}
