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
						{{ __('Vendor Stock Import') }}
					</div>
					<div class="float-right">
						@can('vendor-stock-create')
							<a class="btn btn-primary btn-sm" href="{{ route('vendorstocks.index') }}"> Back to list</a>
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
					<form action="{{ route('vendorstockimport') }}" method="POST" enctype="multipart/form-data">
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
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="importstock">
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
						{{ __('Vendor Stock Export') }}
					</div>
					<div class="float-right">
						@can('vendor-stock-create')
							<a class="btn btn-primary btn-sm" href="{{ route('vendorstocks.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('vendorstockexport') }}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="stock_date" class="col-form-label text-md-right">{{ __('Stock date') }}</label>
									<input id="stock_date" type="date" class="form-control" name="stock_date" value="{{ old('stock_date') }}"  autocomplete="stock_date" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name') }}</label>
									<select class="form-control" id="vendor_name" name="vendor_name">
										<option value="">-- Select --</option>
										@foreach ($vendor as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('vendor_name') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="isbnno" class="col-form-label text-md-right">{{ __('ISBN no') }}</label>
									<input id="isbnno" type="text" class="form-control" name="isbnno" value="{{ old('isbnno') }}"  autocomplete="isbnno" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" >
								</div>
							</div>
							
							
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="author" class="col-form-label text-md-right">{{ __('Author') }}</label>
									<input id="author" type="text" class="form-control" name="author" value="{{ old('author') }}"  autocomplete="author" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="publisher" class="col-form-label text-md-right">{{ __('Publisher') }}</label>
									<input id="publisher" type="text" class="form-control" name="publisher" value="{{ old('publisher') }}"  autocomplete="publisher" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="binding_type" class="col-form-label text-md-right">{{ __('Binding type') }}</label>
									<select class="form-control" id="binding_type" name="binding_type">
										<option value="">-- Select --</option>
										@foreach ($binding as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('binding_type') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="currency" class="col-form-label text-md-right">{{ __('Currency') }}</label>
									<select class="form-control" id="currency" name="currency">
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
                                <button type="submit" class="btn btn-primary" id="exportstock">
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
<script src="{{ asset('js/vendorstock.js') }}" defer></script>
@endsection