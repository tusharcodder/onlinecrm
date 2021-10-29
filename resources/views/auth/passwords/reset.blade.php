@extends('layouts.IndexContent')

@section('indexcontent')
<div class="container">
    <div class="row justify-content-center">
	 <div>
      <div class="container ht-100p">
        <div class="ht-100p d-flex flex-column align-items-center justify-content-center">
          <div class="wd-150 wd-sm-250 mg-b-30"><img src="{{asset('assets/assets/img/img17.png')}}" class="img-fluid" alt=""></div>
          <h4 class="tx-20 tx-sm-24">{{ __('Reset Password') }}</h4>         
          <div class="tx-13 tx-lg-14 mg-b-40" style="display:none">
            <a href="" class="btn btn-brand-02 d-inline-flex align-items-center" ></a>
            <a href="" class="btn btn-white d-inline-flex align-items-center mg-l-5"></a>
          </div>
          
        </div>
      </div><!-- container -->
    </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: #03ceed !important;">Create your New Password</div>

                <div class="card-body" style="border-width: 3px !important;
	border: 1px solid rgba(72, 94, 144, 0.16);">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
