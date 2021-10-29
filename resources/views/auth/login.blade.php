@extends('layouts.IndexContent')

@section('indexcontent')
<div class="container">
    <div class="row justify-content-center">
	<div class="media-body align-items-center d-none d-lg-flex">
            <div class="mx-wd-600">
              <img src="{{asset('assets/assets/img/img15.png')}}" class="img-fluid" alt="">
            </div>
            
          </div>
        <div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
            <div class="wd-100p">
                <h3 class="tx-color-01 mg-b-5">Sign In</h3>
              <p class="tx-color-03 tx-16 mg-b-40">Welcome back! Please signin to continue.</p>

                <div class="form-group">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email" >{{ __('E-Mail Address') }}</label>

                            <div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" >{{ __('Password') }}</label>

                            <div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div>
                                <button type="submit" class="btn btn-brand-02 btn-block">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a> 
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
				 <!--<div class="divider-text">or</div>
              <button class="btn btn-outline-facebook btn-block">Sign In With Facebook</button>
              <button class="btn btn-outline-twitter btn-block">Sign In With Twitter</button>
              <div class="tx-13 mg-t-20 tx-center">Don't have an account? <a href="page-signup.html">Create an Account</a></div>-->
            </div>
        </div>
    </div>
</div>
@endsection
