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
						{{ __('Purchase Order Import') }}
					</div>
					<div class="float-right">
						@can('sale-create')
							<a class="btn btn-primary btn-sm" href="{{ route('purchaseorders.index') }}"> Back to list</a>
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
					<form action="{{ route('purchaseorderimport') }}" method="POST" enctype="multipart/form-data">
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
									<label for="import_from_date" class="col-form-label text-md-right">{{ __('From purchase order date*') }}</label>
									<input id="import_from_date" type="date" class="form-control" name="import_from_date" value="{{ old('import_from_date',date('Y-m-d')) }}"  autocomplete="import_from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="import_to_date" class="col-form-label text-md-right">{{ __('To purchase order date*') }}</label>
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
						{{ __('Purchase Order Export') }}
					</div>
					<div class="float-right">
						@can('sale-create')
							<a class="btn btn-primary btn-sm" href="{{ route('purchaseorders.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('purchaseorderexport') }}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="bill_no" class="col-form-label text-md-right">{{ __('Bill no') }}</label>
									<input id="bill_no" type="text" class="form-control" name="bill_no" value="{{ old('bill_no') }}"  autocomplete="bill_no" >
								</div>
							</div>								
                            <div class="col-md-3">
								<div class="form-group">
									<label for="isbn13" class="col-form-label text-md-right">{{ __('ISBN13') }}</label>
									<input id="isbn13" type="text" class="form-control" name="isbn13" value="{{ old('isbn13') }}"  autocomplete="isbn13" >
								</div>
							</div>
                            <div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name') }}</label>
									<select class="form-control" id="vendor_name" name="vendor_name">
										<option value="">-- Select --</option>
										@foreach ($vendors as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('vendor_name') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>	
							<div class="col-md-3">
								<div class="form-group">
									<label for="quantity" class="col-form-label text-md-right">{{ __('Quantity') }}</label>
									<input id="quantity" type="text" class="form-control" name="quantity" value="{{ old('quantity') }}"  autocomplete="quantity" >
								</div>
							</div>	
						</div>
						
						<div class="row">
													
							<div class="col-md-3">
								<div class="form-group">
									<label for="mrp" class="col-form-label text-md-right">{{ __('MRP') }}</label>
									<input id="mrp" type="text" class="form-control" name="mrp" value="{{ old('mrp') }}"  autocomplete="mrp" >
								</div>
							</div>							
							<div class="col-md-3">
								<div class="form-group">
									<label for="discount" class="col-form-label text-md-right">{{ __('Discout') }}</label>
									<input id="discount" type="text" class="form-control" name="discount" value="{{ old('discount') }}"  autocomplete="discount" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="purchase_by" class="col-form-label text-md-right">{{ __('Purchase By') }}</label>
									<input id="purchase_by" type="text" class="form-control" name="purchase_by" value="{{ old('purchase_by') }}"  autocomplete="purchase_by">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="purchase_date_from" class="col-form-label text-md-right">{{ __('Purchase date from*') }}</label>
									<input id="purchase_date_from" type="date" class="form-control" name="purchase_date_from" value="{{ old('purchase_date_from',date('Y-m-d')) }}"  autocomplete="purchase_date_from" required>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="purchase_date_to" class="col-form-label text-md-right">{{ __('Purchase date to*') }}</label>
									<input id="purchase_date_to" type="date" class="form-control" name="purchase_date_to" value="{{ old('purchase_date_to',date('Y-m-d')) }}"  autocomplete="purchase_date_to" required>
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
<script src="{{ asset('js/purchseorder.js') }}" defer></script>
@endsection