@extends('layouts.IndexContent')

@section('indexcontent')
<div class="container">
    <div class="row justify-content-center">
	<div class="mx-wd-300 wd-sm-450 ht-100p d-flex flex-column align-items-center justify-content-center">
          <div class="wd-80p wd-sm-300 mg-b-15"><img src="{{asset('assets/assets/img/img18.png')}}" class="img-fluid" alt=""></div>
          <h4 class="tx-20 tx-sm-24">{{ __('Reset Password') }}</h4>
          <p class="tx-color-03 mg-b-30 tx-center">Enter your Email address and we will send you a link to reset your password.</p>
          <div >
		  <div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="wd-100p d-flex flex-column flex-sm-row mg-b-40">
                        @csrf                        
                            

                            
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <button type="submit" class="btn btn-brand-02 mg-sm-l-10 mg-t-10 mg-sm-t-0" style="width:350px">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            
                    </form>
					<span class="tx-12 tx-color-03">Back to login <a href="{{route('login')}}">click here</a></span>
                </div>
           
          </div>
          

        </div>	
        
    </div>
</div>
@endsection
