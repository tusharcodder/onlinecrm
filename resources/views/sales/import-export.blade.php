@extends('layouts.content')
@section('content')
<div class="container-fluid mb-2">
	<div class="row justify-content-center">
		<div class="col-md-12">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			@if ($message = Session::get('success'))
				<div class="alert alert-success">
					<p>{{ $message }}</p>
				</div>
			@endif
			@if ($errormessage = Session::get('error'))
				<div class="alert alert-danger">
					<p>{{ $errormessage }}</p>
				</div>
			@endif
		</div>
	</div>
</div>
<div class="container-fluid mb-3">
    <div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Sale Import') }}
					</div>
					<div class="float-right">
						@can('sale-create')
							<a class="btn btn-primary btn-sm" href="{{ route('sales.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<h6 class="txt-dark capitalize-font"><i class="fa fa-file"></i> File to import:</h6>
					<hr class="light-grey-hr">
					<span class="help-block mb-3">
						<small>	<i class="fa fa-upload"></i> File extension allow to import: (xls, xlsx, csv)</small><br>
						<small>	<i class="fa fa-save"></i> Max upload size: 500MB</small><br>
					</span>
					<form action="{{ route('saleimport') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row mt-3">
							<div class="col-md-5">
								<div class="form-group mb-20">
									<label class="control-label mb-10 text-left" for="importfile">Browse your computer*</label>
									<input type="file" class="form-control" name="importfile" id="importfile" accept=".csv,.xls,.xlsx" autofocus required>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group mb-20">
									<label class="control-label mb-10 text-left" for="importtype">Import Type*</label>
									<select class="form-control" id="importtype" name="importtype" required>
										<option value="newimport">New import</option>
										<option value="importwithupdate">Delete with new</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row importupdate" style="display:none;">	
							<div class="col-md-3">
								<div class="form-group">
									<label for="import_from_date" class="col-form-label text-md-right">{{ __('From sale date*') }}</label>
									<input id="import_from_date" type="date" class="form-control" name="import_from_date" value="{{ old('import_from_date',date('Y-m-d')) }}"  autocomplete="import_from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="import_to_date" class="col-form-label text-md-right">{{ __('To sale date*') }}</label>
									<input id="import_to_date" type="date" class="form-control" name="import_to_date" value="{{ old('import_to_date',date('Y-m-d')) }}"  autocomplete="import_to_date">
								</div>
							</div>
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="importsale">
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

<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Sale Export') }}
					</div>
					<div class="float-right">
						@can('sale-create')
							<a class="btn btn-primary btn-sm" href="{{ route('sales.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('saleexport') }}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="invoice_no" class="col-form-label text-md-right">{{ __('Invoice no') }}</label>
									<input id="invoice_no" type="text" class="form-control" name="invoice_no" value="{{ old('invoice_no') }}"  autocomplete="invoice_no" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_type" class="col-form-label text-md-right">{{ __('Vendor type') }}</label>
									<input id="vendor_type" type="text" class="form-control" name="vendor_type" value="{{ old('vendor_type') }}" autocomplete="vendor_type" />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name') }}</label>
									<input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name') }}"  autocomplete="vendor_name" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="aggregator_vendor_name" class="col-form-label text-md-right">{{ __('Aggregator vendor name') }}</label>
									<input id="aggregator_vendor_name" type="text" class="form-control" name="aggregator_vendor_name" value="{{ old('aggregator_vendor_name') }}" autocomplete="aggregator_vendor_name"/>
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="brand" class="col-form-label text-md-right">{{ __('Brand') }}</label>
									<input id="brand" type="text" class="form-control" name="brand" value="{{ old('brand') }}"  autocomplete="brand" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="category" class="col-form-label text-md-right">{{ __('Category') }}</label>
									<input id="category" type="text" class="form-control" name="category" value="{{ old('category') }}"  autocomplete="category" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="colour" class="col-form-label text-md-right">{{ __('Colour') }}</label>
									<input id="colour" type="text" class="form-control" name="colour" value="{{ old('colour') }}"  autocomplete="colour" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Product code') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code') }}"  autocomplete="product_code" >
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date" class="col-form-label text-md-right">{{ __('From sale date*') }}</label>
									<input id="from_date" type="date" class="form-control" name="from_date" value="{{ old('from_date',date('Y-m-d')) }}"  autocomplete="from_date" required>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="to_date" class="col-form-label text-md-right">{{ __('To sale date*') }}</label>
									<input id="to_date" type="date" class="form-control" name="to_date" value="{{ old('to_date',date('Y-m-d')) }}"  autocomplete="to_date" required>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-form-label text-md-right" for="format">{{ __('Format*') }}</label>
									<select class="form-control" id="format" name="format">
										<option value="withheading">Heading with data</option>
										<option value="heading">Only heading</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-form-label text-md-right" for="exporttype">{{ __('File Type*') }}</label>
									<select class="form-control" id="exporttype" name="exporttype">
										<option value="csv">CSV</option>
										<option value="xls">XLS</option>
										<option value="xlsx">XLSX</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="exportsale">
                                    {{ __('Download') }}
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
<script src="{{ asset('js/sale.js') }}" defer></script>
@endsection