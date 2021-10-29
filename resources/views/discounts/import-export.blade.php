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
						{{ __('Discount Import') }}
					</div>
					<div class="float-right">
						@can('discount-create')
							<a class="btn btn-primary btn-sm" href="{{ route('discounts.index') }}"> Back to list</a>
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
						<small>	<i class="fa fa-calendar"></i> Date/time format should be like that: 2021-04-27T14:48</small>
					</span>
					<form action="{{ route('discountimport') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row mt-3">
							<div class="col-md-6">
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
									<label for="import_from_date" class="col-form-label text-md-right">{{ __('From date*') }}</label>
									<input id="import_from_date" type="date" class="form-control" name="import_from_date" value="{{ old('import_from_date',date('Y-m-d')) }}"  autocomplete="import_from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="import_to_date" class="col-form-label text-md-right">{{ __('To date*') }}</label>
									<input id="import_to_date" type="date" class="form-control" name="import_to_date" value="{{ old('import_to_date',date('Y-m-d')) }}"  autocomplete="import_to_date">
								</div>
							</div>
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="importdiscount">
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
						{{ __('Discount Export') }}
					</div>
					<div class="float-right">
						@can('discount-create')
							<a class="btn btn-primary btn-sm" href="{{ route('discounts.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('discountexport') }}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Type') }}</label>
									<select class="form-control" id="type" name="type">
										<option value="">-- Select --</option>
										@foreach ($type as $key => $val)
											<option value="{{ $val }}" {{ $val == old('type') ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor" class="col-form-label text-md-right">{{ __('Vendor') }}</label>
									<select class="form-control" id="vendor" name="vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group aggregatorcontainer" style="display:none;">
									<label for="aggregator_vendor" class="col-form-label text-md-right">{{ __('Aggregator vendor') }}</label>

									<select class="form-control" id="aggregator_vendor" name="aggregator_vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Product code') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code') }}"  autocomplete="product_code" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Discount(%)') }}</label>
									<input id="discount" type="number" class="form-control" name="discount" value="{{ old('discount') }}" step="any" min="0" max="100" autocomplete="discount" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date" class="col-form-label text-md-right">{{ __('From date*') }}</label>
									<input id="from_date" type="datetime-local" class="form-control" name="from_date" value="{{ old('from_date',date('Y-m-d')) }}"  autocomplete="from_date" required>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="to_date" class="col-form-label text-md-right">{{ __('To date*') }}</label>
									<input id="to_date" type="datetime-local" class="form-control" name="to_date" value="{{ old('to_date',date('Y-m-d')) }}"  autocomplete="to_date" required>
								</div>
							</div>
						</div>
						
						<div class="row">
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
                                <button type="submit" class="btn btn-primary" id="exportdiscount">
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
<script src="{{ asset('js/discount.js') }}" defer></script>
@endsection