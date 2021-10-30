<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
	
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = "/register";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function showRegistrationForm()
    {
        $roles = Role::where('id','!=','1')->pluck('name','name')->all();
		return view('auth/register',compact('roles'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
			'role' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
		$user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
		
		// user assign role
		$user->assignRole($data['role']);
		
		// email data
		$data = array(
			'name' => $data['name'],
			'email' => $data['email'],
			'role' => $data['role'],
			'password' => $data['password'],
		);
		
		//Mail::to($data['email'])->send(new WelcomeMail($data));
		
		/* // send email with the template
		Mail::send('welcome_email', $email_data, function ($message) use ($email_data) {
			$message->to($email_data['email'], $email_data['name'])
				->subject('Welcome to MyNotePaper')
				->from('info@mynotepaper.com', 'MyNotePaper');
		}); */

		return $user;
    }
	
	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$this->validator($request->all())->validate();
		event(new Registered($user = $this->create($request->all())));
		
		// $this->guard()->login($user);
		
		return redirect()->route('users.index')
                        ->with('success','User registered successfully.');
						
		/* session()->flash('message', 'User registered successfully.');
		return $this->registered($request, $user)
							?: redirect($this->redirectPath()); */
	 }
}