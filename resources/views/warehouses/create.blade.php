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