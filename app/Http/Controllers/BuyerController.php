<?php

namespace App\Http\Controllers;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Rules\Emails; // multiple email rule validation
use DB;

class BuyerController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:manufacturer-list|manufacturer-create|manufacturer-edit|manufacturer-delete', ['only' => ['index','store']]);
		$this->middleware('permission:manufacturer-list', ['only' => ['index']]);
		$this->middleware('permission:manufacturer-create', ['only' => ['create','store']]);
		$this->middleware('permission:manufacturer-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:manufacturer-delete', ['only' => ['destroy']]);
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
		$buyers = Buyer::where(function($query) use ($search) {
					$query->where('name','LIKE','%'.$search.'%')
						->orWhere('country','LIKE','%'.$search.'%')	
						->orWhere('address','LIKE','%'.$search.'%');
				})->orderBy('id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $buyers->appends ( array (
			'search' => $search
		));
		
        return view('buyers.index',compact('buyers','search'))
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
        return view('buyers.create');
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
		$request->validate([
			'name' => 'required|unique:buyers,name',
			'country' => 'required',
            'addmore.*.cname' => 'required',
			'addmore.*.cemail' => ['nullable', new Emails],
        ],
		[
			'addmore.*.cname.required' => 'Each person must have a name.',
		]);

		// save value in db
		$buyer = Buyer::create(['name' => $request->input('name'),'country' => $request->input('country'),'address' => $request->input('address'),'created_by' => $uid,'updated_by' => $uid]);
		
		// last insert id of buyer
		$buyerid= $buyer->id;
		
		// save contact person info into table contact_person_has_buyers table
		if(!empty($request->input('addmore'))){
			foreach($request->input('addmore') as $val){
				if(!empty($val['cname'])){
					$data[] = [
						'buyer_id' => $buyerid,
						'contact_person_name' => $val['cname'],
						'contact_person_email' => $val['cemail'],
						'contact_person_number' => $val['cphone']
					];
				}
			}
			if(!empty($data))
				DB::table('contact_person_has_buyers')->insert($data);
		}
		
		return redirect()->route('buyers.index')
                        ->with('success','Buyer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$buyer = Buyer::find($id);
        $buyercontactperdetails = Buyer::select('contact_person_has_buyers.*')->join("contact_person_has_buyers","contact_person_has_buyers.buyer_id","=","buyers.id")
            ->where("buyers.id",$id)
            ->get();


        return view('buyers.show',compact('buyer','buyercontactperdetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		$buyer = Buyer::find($id);
        $buyercontactperdetails = Buyer::select('contact_person_has_buyers.contact_person_name as cname','contact_person_has_buyers.contact_person_email as cemail','contact_person_has_buyers.contact_person_number as cphone','contact_person_has_buyers.id as cid')->join("contact_person_has_buyers","contact_person_has_buyers.buyer_id","=","buyers.id")
            ->where("buyers.id",$id)
            ->get()->toArray();

        return view('buyers.edit',compact('buyer','buyercontactperdetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		$user = Auth::user();
		$uid = $user->id;
        //
		$request->validate([
			'name' => 'required|unique:buyers,name,'.$id,
			'country' => 'required',
            'addmore.*.cname' => 'required',
			'addmore.*.cemail' => ['nullable', new Emails],
        ],
		[
			'addmore.*.cname.required' => 'Each person must have a name.',
		]);

		// update value in db
		$buyer = Buyer::find($id);
        $buyer->name = $request->input('name');
        $buyer->country = $request->input('country');
        $buyer->address = $request->input('address');
        $buyer->updated_by = $uid;
        $buyer->save();
		
		// last insert id of buyer
		$buyerid= $id;
		
		// save contact person info into table contact_person_has_buyers table
		if(!empty($request->input('addmore'))){
			// delete all records from contact person buyers
			DB::table("contact_person_has_buyers")->where('buyer_id',$buyerid)->delete();
			
			foreach($request->input('addmore') as $val){
				if(!empty($val['cname'])){
					$data[] = [
						'buyer_id' => $buyerid,
						'contact_person_name' => $val['cname'],
						'contact_person_email' => $val['cemail'],
						'contact_person_number' => $val['cphone']
					];
				}
			}
			if(!empty($data))
				DB::table('contact_person_has_buyers')->insert($data);
		}
		
		return redirect()->route('buyers.index')
                        ->with('success','Buyer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		DB::table("buyers")->where('id',$id)->delete();
        return redirect()->route('buyers.index')
                        ->with('success','Buyer deleted successfully.');
    }
}
