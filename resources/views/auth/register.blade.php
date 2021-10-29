@extends('layouts.content')

@section('content')
<div class="container  pd-x-0 pd-lg-x-10 pd-xl-x-0">
    <div class="media align-items-stretch justify-content-center ht-100p">
        <div class="sign-wrapper mg-lg-r-50 mg-xl-r-60">
            <div class="pd-t-20 wd-100p">				
					<h4 class="tx-color-01 mg-b-5">
						{{ __('Create New User') }}
					</h4>
					<p class="tx-color-03 tx-16 mg-b-40">It's free to signup and only takes a minute.</p>
					<div class="float-right" style="display:none">
						<a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> Back to list</a>
					</div>
					
				

                <div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
							<div class="d-flex justify-content-between mg-b-5">
								<label for="name" class="mg-b-0-f">{{ __('Name*') }}</label>
							</div>                 
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Enter your Name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="form-group">
							<div class="d-flex justify-content-between mg-b-5">
                            <label for="email" class="mg-b-0-f">{{ __('E-Mail Address*') }}</label>   </div>                         
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" placeholder="Enter your Email" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>
						
						<div class="form-group">
							<div class="d-flex justify-content-between mg-b-5">
								<label for="role" class="mg-b-0-f">{{ __('Role*') }}</label>
								</div>
								
									<select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
										<option value="">-- Select --</option>
										@foreach ($roles as $key => $val)
											<option value="{{ $key }}" {{ ($key == old('role')) ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
									@error('role')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
								
							</div>
							
                        <div class="form-group">
							<div class="d-flex justify-content-between mg-b-5">
                            <label for="password" class="mg-b-0-f">{{ __('Password*') }}</label>
							</div>
                            
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter your password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="form-group">
							<div class="d-flex justify-content-between mg-b-5">
                            <label for="password-confirm" class="mg-b-0-f">{{ __('Confirm Password*') }}</label>
							</div>
                            
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Enter Confirm password">
                           
                        </div>
						
						@if (session('message'))
							<div class="form-group row">
								<div class="col-md-6 offset-md-4">
									<div class="alert alert-success" role="alert">
										{{ session('message') }}
									</div>
								</div>
							</div>
						@endif

                        <div class="form-group">
                           <div class="float-left">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
							</div>
                            <div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"><span style="font-size:large"> Back to list</span></a>
					</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		<div class="media-body pd-y-30 pd-lg-x-50 pd-xl-x-60 align-items-center d-none d-lg-flex pos-relative">
            <div class="mx-lg-wd-500 mx-xl-wd-550">
              <img src="{{asset('assets/assets/img/img16.png')}}" class="img-fluid" alt="">
            </div>
            
          </div>
    </div>

</div>
@endsection
