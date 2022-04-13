@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Warehouse*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('warehouse.index') }}"> Back to list</a>
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
					@if ($message = Session::get('error'))
						<div class="alert alert-danger">
							<p>{{ $message }}</p>
						</div>
					@endif
					<form method="POST" action="{{ route('warehouse.store') }}">
					@csrf
					
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Name*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="country_code" class="col-form-label text-md-right">{{ __('Country Code*') }}</label>
									<input id="country_code" type="text" class="form-control" name="country_code" value="{{ old('country_code') }}" autocomplete="country_code" minlength="2" maxlength="2" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<br/> <label for="country_code" class="col-form-label text-md-right">{{ __('Is Shipped') }}</label>
									
									&nbsp;	&nbsp;  <input id="is_shipped" type="checkbox" name="is_shipped"/> 
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="email" class="col-form-label text-md-right">{{ __('Email') }}</label>
									<input type="email" id="email" name="email" class="form-control"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="phone" class="col-form-label text-md-right">{{ __('Phone*') }}</label>
									<input type="number" id="phone" name="phone" class="form-control" required/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="address" class="col-form-label text-md-right">{{ __('Address*') }}</label>
									<textarea id="address" name="address" class="form-control" required></textarea>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="city" class="col-form-label text-md-right">{{ __('City*') }}</label>
									<input id="city" type="text" required class="form-control" name="city" value="{{ old('city') }}" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="state" class="col-form-label text-md-right">{{ __('State e.g US*') }}</label>
									<input id="state" type="text" required class="form-control" name="state" value="{{ old('state') }}" autocomplete="state" minlength="2" maxlength="2" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="postal_code" class="col-form-label text-md-right">{{ __('Postal Code*') }}</label>
									<input id="postalcode" type="text" required class="form-control" name="postal_code" value="{{ old('postalcode') }}" / >
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