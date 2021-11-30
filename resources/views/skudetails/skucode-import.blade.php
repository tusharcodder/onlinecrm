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
						{{ __('Sku Import') }}
					</div>
					<div class="float-right">
						@can('skucode-create')
							<a class="btn btn-primary btn-sm" href="{{ route('skudetails.index') }}"> Back to list</a>
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
					<form action="{{ route('skucodedetailimport') }}" method="POST" enctype="multipart/form-data">
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
						{{ __('Sku Export') }}
					</div>
					<div class="float-right">
						@can('vendor-stock-create')
							<a class="btn btn-primary btn-sm" href="{{ route('skudetails.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('skucodedetailexport') }}" method="POST">
						@csrf
						<div class="row">	
							<div class="col-md-3">
								<div class="form-group">
									<label for="market_place" class="col-form-label text-md-right">{{ __('Market Place') }}</label>
									<select class="form-control" id="market_place" name="market_place">
										<option value="">-- Select --</option>
										@foreach ($marketplaces as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('market_place') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
								<label for="warehouse" class="col-form-label text-md-right">{{ __('Warehouse') }}</label>
									<select class="form-control" id="warehouse" name="warehouse">
										<option value="">-- Select --</option>
										@foreach ($warehouses as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == old('warehouse') ? 'selected' : '' }}>{{ $val->name }}</option>
										@endforeach
									</select>
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
									<label for="author" class="col-form-label text-md-right">{{ __('ISBN10') }}</label>
									<input id="author" type="text" class="form-control" name="author" value="{{ old('isbn10') }}"  autocomplete="isbn10" >
								</div>
							</div>
							
						</div>
						
						<div class="row">							
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="skucode" class="col-form-label text-md-right">{{ __('Sku Code') }}</label>
									<input id="skucode" type="text" class="form-control" name="skucode" value="{{ old('skucode') }}"  autocomplete="skucode" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="mrp" class="col-form-label text-md-right">{{ __('MRP') }}</label>
									<input id="mrp" type="text" class="form-control" name="mrp" value="{{ old('mrp') }}"  autocomplete="mrp" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="discount" class="col-form-label text-md-right">{{ __('Discount(%)') }}</label>
									<input id="discount" type="text" class="form-control" name="discount" value="{{ old('discount') }}"  autocomplete="discount" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="weight" class="col-form-label text-md-right">{{ __('Weight(kg)') }}</label>
									<input id="weight" type="text" class="form-control" name="weight" value="{{ old('weight') }}"  autocomplete="weight" >
								</div>
							</div>
						</div>
						
						<div class="row">
						<div class="col-md-3">
								<div class="form-group">
									<label for="pkg_weight" class="col-form-label text-md-right">{{ __('pkg_weight(kg)') }}</label>
									<input id="pkg_weight" type="text" class="form-control" name="pkg_weight" value="{{ old('pkg_weight') }}"  autocomplete="pkg_weight" >
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
