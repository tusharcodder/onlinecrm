@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Add New Stock Item') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('stocks.index') }}"> Back to list</a>
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
					
					<form method="POST" action="{{ route('stocks.store') }}" id="stockform" enctype = 'multipart/form-data'>
					@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="manufacturer_name" class="col-form-label text-md-right">{{ __('Manufacturer name*') }}</label>
									<input id="manufacturer_name" type="text" class="form-control" name="manufacturer_name" value="{{ old('manufacturer_name') }}"  autocomplete="manufacturer_name" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="country" class="col-form-label text-md-right">{{ __('Country') }}</label>
									<input id="country" type="text" class="form-control" name="country" value="{{ old('country') }}"  autocomplete="country"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="manufacture_date" class="col-form-label text-md-right">{{ __('Manufacture date*') }}</label>
									<input id="manufacture_date" type="date" class="form-control" name="manufacture_date" value="{{ old('manufacture_date') }}"  autocomplete="manufacture_date" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="cost" class="col-form-label text-md-right">{{ __('Cost') }}</label>
									<input id="cost" type="number" step="any" class="form-control" name="cost" value="{{ old('cost') }}" autocomplete="cost" />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="stock_date" class="col-form-label text-md-right">{{ __('Stock date*') }}</label>
									<input id="stock_date" type="date" class="form-control" name="stock_date" value="{{ old('stock_date') }}"  autocomplete="stock_date" required>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="brand" class="col-form-label text-md-right">{{ __('Brand*') }}</label>
									<input id="brand" type="text" class="form-control" name="brand" value="{{ old('brand') }}"  autocomplete="brand" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="category" class="col-form-label text-md-right">{{ __('Category*') }}</label>
									<input id="category" type="text" class="form-control" name="category" value="{{ old('category') }}"  autocomplete="category" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="gender" class="col-form-label text-md-right">{{ __('Gender') }}</label>
									<input id="gender" type="text" class="form-control" name="gender" value="{{ old('gender') }}"  autocomplete="gender"/>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="colour" class="col-form-label text-md-right">{{ __('Colour') }}</label>
									<input id="colour" type="text" class="form-control" name="colour" value="{{ old('colour') }}"  autocomplete="colour"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="size" class="col-form-label text-md-right">{{ __('Size*') }}</label>
									<input id="size" type="text" class="form-control" name="size" value="{{ old('size') }}"  autocomplete="size" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="lotno" class="col-form-label text-md-right">{{ __('Lotno*') }}</label>
									<input id="lotno" type="text" class="form-control" name="lotno" value="{{ old('lotno') }}"  autocomplete="lotno" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="sku_code" class="col-form-label text-md-right">{{ __('Sku code*') }}</label>
									<input id="sku_code" type="text" class="form-control" name="sku_code" value="{{ old('sku_code') }}"  autocomplete="sku_code" required />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_code" class="col-form-label text-md-right">{{ __('Product code*') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code') }}"  autocomplete="product_code" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="hsn_code" class="col-form-label text-md-right">{{ __('Hsn code') }}</label>
									<input id="hsn_code" type="text" class="form-control" name="hsn_code" value="{{ old('hsn_code') }}"  autocomplete="hsn_code" />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="online_mrp" class="col-form-label text-md-right">{{ __('Online mrp*') }}</label>
									<input id="online_mrp" type="number" class="form-control" name="online_mrp" value="{{ old('online_mrp') }}" step="any" autocomplete="online_mrp" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="offline_mrp" class="col-form-label text-md-right">{{ __('Offline mrp*') }}</label>
									<input id="offline_mrp" type="number" class="form-control" name="offline_mrp" value="{{ old('offline_mrp') }}" step="any" autocomplete="offline_mrp" required/>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="quantity" class="col-form-label text-md-right">{{ __('Quantity*') }}</label>
									<input id="quantity" type="number" class="form-control" name="quantity" value="{{ old('quantity') }}" step="any" autocomplete="quantity" required />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="product_image" class="col-form-label text-md-right">{{ __('Product image') }}</label>
									<input id="product_image" type="file" class="form-control" name="product_image" value="{{ old('file') }}" accept=".jpg,.jpeg,.png,.gif,.svg" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="">
									<img src="#" alt="Preview" id="preview" class="img-thumbnail" width="25%" style="display:none;">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<label for="description" class="col-form-label text-md-right">{{ __('Description') }}</label>
									<textarea class="form-control" rows="4" name="description" id="description">{{ old('description') }}</textarea>
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
<script src="{{ asset('js/stock.js') }}" defer></script>
@endsection