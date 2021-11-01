@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Vendor Stock Item*') }}
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
					
					<form method="POST" action="{{ route('vendorstocks.update', $stock->id) }}" id="stockform" enctype = 'multipart/form-data'>
					@csrf
					@method('PATCH')
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="stock_date" class="col-form-label text-md-right">{{ __('Stock date*') }}</label>
									<input id="stock_date" type="date" class="form-control" name="stock_date" value="{{ old('stock_date', $stock->stock_date) }}"  autocomplete="stock_date" required>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name*') }}</label>
									<input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name', $stock->vendor_name) }}"  autocomplete="vendor_name" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="isbnno" class="col-form-label text-md-right">{{ __('ISBN no*') }}</label>
									<input id="isbnno" type="text" class="form-control" name="isbnno" value="{{ old('isbnno', $stock->isbnno) }}"  autocomplete="isbnno" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Name*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name', $stock->name) }}"  autocomplete="name" required />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="author" class="col-form-label text-md-right">{{ __('Author*') }}</label>
									<input id="author" type="text" class="form-control" name="author" value="{{ old('author', $stock->author) }}"  autocomplete="author" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="publisher" class="col-form-label text-md-right">{{ __('Publisher*') }}</label>
									<input id="publisher" type="text" class="form-control" name="publisher" value="{{ old('publisher', $stock->publisher) }}"  autocomplete="publisher" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="binding_type" class="col-form-label text-md-right">{{ __('Binding type*') }}</label>
									<input id="binding_type" type="text" class="form-control" name="binding_type" value="{{ old('binding_type', $stock->binding_type) }}"  autocomplete="binding_type" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="currency" class="col-form-label text-md-right">{{ __('Currency*') }}</label>
									<input id="currency" type="text" class="form-control" name="currency" value="{{ old('currency', $stock->currency) }}"  autocomplete="currency" required />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="price" class="col-form-label text-md-right">{{ __('Price*') }}</label>
									<input id="price" type="number" step="any" class="form-control" name="price" value="{{ old('price', $stock->price) }}"  autocomplete="price" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="discount" class="col-form-label text-md-right">{{ __('Discount*') }}</label>
									<input id="discount" type="number" step="any" class="form-control" name="discount" value="{{ old('discount', $stock->discount) }}"  autocomplete="discount" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="quantity" class="col-form-label text-md-right">{{ __('Quantity*') }}</label>
									<input id="quantity" type="number" step="any" class="form-control" name="quantity" value="{{ old('quantity', $stock->quantity) }}"  autocomplete="quantity" required />
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