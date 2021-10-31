@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Vendor*') }}
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
		
					<form method="POST" action="{{ route('vendorss.update',$vendor->id) }}">
					@csrf
					@method('PATCH')
						<div class="row">				
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Name*') }}</label>
									<input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name',$vendor->name) }}"  autocomplete="vendor_name" required>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="cphone" class="col-form-label text-md-right">{{ __('Phone number') }}</label>
									<input id="cphone" type="number" class="form-control" name="cphone" value="{{ old('cphone',$vendor->number) }}"  autocomplete="cphone" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="cemail" class="col-form-label text-md-right">{{ __('Email') }}</label>
									<input id="cemail" type="email" class="form-control" name="cemail" value="{{ old('cemail',$vendor->email) }}"  autocomplete="cemail"  >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="priority" class="col-form-label text-md-right">{{ __('Priority*') }}</label>
									<input id="priority" type="number" class="form-control" name="priority" value="{{ old('priority',$vendor->priority) }}"  autocomplete="priority" required>
								</div>
							</div>
						</div>						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="address" class="col-form-label text-md-right">{{ __('Address') }}</label>
									<textarea id="address" class="form-control" name="address" rows="4" cols="50">{{ old('address',$vendor->address) }}</textarea>
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