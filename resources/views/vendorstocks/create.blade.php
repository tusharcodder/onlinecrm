@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Add New Vendor Stock Item*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('vendorstocks.index') }}"> Back to list</a>
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
					
					<form method="POST" action="{{ route('vendorstocks.store') }}" id="stockform" enctype = 'multipart/form-data'>
					@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="stock_date" class="col-form-label text-md-right">{{ __('Stock date*') }}</label>
									<input id="stock_date" type="date" class="form-control" name="stock_date" value="{{ old('stock_date') }}"  autocomplete="stock_date" required>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name*') }}</label>
									<select class="form-control" id="vendor_name" name="vendor_name" required>
										<option value="">-- Select --</option>
										@foreach ($vendor as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('vendor_name') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="isbnno" class="col-form-label text-md-right">{{ __('ISBN no*') }}</label>
									<input id="isbnno" type="text" class="form-control" name="isbnno" value="{{ old('isbnno') }}"  autocomplete="isbnno" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Name*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" required />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="author" class="col-form-label text-md-right">{{ __('Author*') }}</label>
									<input id="author" type="text" class="form-control" name="author" value="{{ old('author') }}"  autocomplete="author" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="publisher" class="col-form-label text-md-right">{{ __('Publisher*') }}</label>
									<input id="publisher" type="text" class="form-control" name="publisher" value="{{ old('publisher') }}"  autocomplete="publisher" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="binding_type" class="col-form-label text-md-right">{{ __('Binding type*') }}</label>
									<select class="form-control" id="binding_type" name="binding_type" required>
										<option value="">-- Select --</option>
										@foreach ($binding as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('binding_type') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="currency" class="col-form-label text-md-right">{{ __('Currency*') }}</label>
									<select class="form-control" id="currency" name="currency" required>
										<option value="">-- Select --</option>
										@foreach ($currency as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('currency') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="price" class="col-form-label text-md-right">{{ __('Price*') }}</label>
									<input id="price" type="number" step="any" class="form-control" name="price" value="{{ old('price') }}"  autocomplete="price" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="discount" class="col-form-label text-md-right">{{ __('Discount*') }}</label>
									<input id="discount" type="number" step="any" class="form-control" name="discount" value="{{ old('discount') }}"  autocomplete="discount" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="quantity" class="col-form-label text-md-right">{{ __('Quantity*') }}</label>
									<input id="quantity" type="number" step="any" class="form-control" name="quantity" value="{{ old('quantity') }}"  autocomplete="quantity" required />
								</div>
							</div>
						</div>
					
						<div class="form-group row mb-0">
                            <div class="col-md-12">
								<button type="submit" id="save_stock" class="btn btn-primary">
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
<script src="{{ asset('js/vendorstock.js') }}" defer></script>
@endsection