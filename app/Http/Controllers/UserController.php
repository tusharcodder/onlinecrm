<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use App\Rules\MatchOldPassword; // old password rule
use Illuminate\Support\Arr;
use DB;
use Hash;

class UserController extends Controller
{
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
		$this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','register']]);
		$this->middleware('permission:user-list', ['only' => ['index']]);
		$this->middleware('permission:user-create', ['only' => ['register','register']]);
		$this->middleware('permission:user-edit', ['only' => ['edit','update']]);
		$this->middleware('permission:user-delete', ['only' => ['destroy']]);
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
		
        $data = User::select('users.id','users.name','users.email')->join("model_has_roles","model_has_roles.model_id","=","users.id")->
				join("roles","roles.id","=","model_has_roles.role_id")->
				where(function($query) use ($search) {
					$query->where('users.name','LIKE','%'.$search.'%')
						->orWhere('users.email','LIKE','%'.$search.'%')	
						->orWhere('roles.name','LIKE','%'.$search.'%');	
				})->where('model_has_roles.role_id','!=','1')->orderBy('users.id','DESC')->paginate(10)->setPath('');
		
		// bind value with pagination link
		$pagination = $data->appends ( array (
			'search' => $search
		));
		
        return view('users.index',compact('data','search'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id','!=','1')->pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'role' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('id','!=','1')->pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:password_confirmation',
            'role' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('role'));
		//$user->revokePermissionTo('edit');
		
        return redirect()->route('users.index')
                        ->with('success','User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully.');
    }
	
	/**
     * Display the resource for show profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProfile($id)
    {
        $user = User::find($id);
        return view('users.profile',compact('user'));
    }
	
	/**
     * Update profile the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
		$input = $request->all();
        $user = User::find($id);
        $user->update($input);
		
        return redirect()->route('profile',$id)
                        ->with('success','Profile updated successfully.');
    }
	
	/**
     * Update password the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        $this->validate($request, [
			'current_password' => ['required', new MatchOldPassword],
            'password' => 'same:password_confirmation',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::find($id);
        $user->update($input);
		
		/* $request->session()->flush();
		
		return redirect()->guest('login'); */
        return redirect()->route('profile',$id)
                        ->with('successpwd','Password updated successfully.');
    }
}