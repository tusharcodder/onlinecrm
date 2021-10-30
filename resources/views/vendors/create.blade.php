@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Vendor*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('vendorss.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
							</ul>
						</div>
					@endif
		
					<form method="POST" action="{{ route('vendorss.store') }}">
					@csrf
					
						<div class="row">					
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name*') }}</label>
									<input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name') }}"  autocomplete="vendor_name" required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="cphone" class="col-form-label text-md-right">{{ __('Phone number') }}</label>
									<input id="cphone" type="text" class="form-control" name="cphone" value="{{ old('cphone') }}"  autocomplete="cphone" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="cemail" class="col-form-label text-md-right">{{ __('Email') }}</label>
									<input id="cemail" type="email" class="form-control" name="cemail" value="{{ old('cemail') }}"  autocomplete="cemail" multiple >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="address" class="col-form-label text-md-right">{{ __('Address') }}</label>
									<textarea id="address" class="form-control" name="address" rows="2" cols="6">{{ old('address') }}</textarea>
								</div>
							</div>
							
						</div>
						
						

						<div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
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
@section('footer-script')
<script src="{{ asset('js/vendor.js') }}" defer></script>
@endsection