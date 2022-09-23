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
						{{ __('Customer Unshipped Order Import') }}
					</div>
					<div class="float-right">
						@can('customer-order-list')
							<a class="btn btn-primary btn-sm" href="{{ route('customerorders.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<h6 class="txt-dark capitalize-font"><i class="fa fa-file"></i> File to import:</h6>
					<hr class="light-grey-hr">
					<span class="help-block mb-3">
						<small>	<i class="fa fa-upload"></i> File extension allow to import: (xls, xlsx, csv, txt)</small><br>
						<small>	<i class="fa fa-save"></i> Max upload size: 500MB</small><br>
						
					</span>
					<form action="{{ route('customerorderimport') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row mt-3">
							<div class="col-md-5">
								<div class="form-group mb-20">
									<label class="control-label mb-10 text-left" for="importfile">Browse your computer*</label>
									<input type="file" class="form-control" name="importfile" id="importfile" accept=".csv,.xls,.xlsx,.txt" autofocus required>
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

<div class="container-fluid mb-3">
    <div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Customer Order Report Import') }}
					</div>
					<div class="float-right">
						@can('customer-order-list')
							<a class="btn btn-primary btn-sm" href="{{ route('customerorders.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<h6 class="txt-dark capitalize-font"><i class="fa fa-file"></i> File to import:</h6>
					<hr class="light-grey-hr">
					<span class="help-block mb-3">
						<small>	<i class="fa fa-upload"></i> File extension allow to import: (xls, xlsx, csv, txt)</small><br>
						<small>	<i class="fa fa-save"></i> Max upload size: 500MB</small><br>
						
					</span>
					<form action="{{ route('customerorderreportimport') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row mt-3">
							<div class="col-md-5">
								<div class="form-group mb-20">
									<label class="control-label mb-10 text-left" for="importfile">Browse your computer*</label>
									<input type="file" class="form-control" name="importfile" id="importfile" accept=".csv,.xls,.xlsx,.txt" autofocus required>
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
						{{ __('Customer Order Export') }}
					</div>
					<div class="float-right">
						@can('customer-order-list')
							<a class="btn btn-primary btn-sm" href="{{ route('customerorders.index') }}"> Back to list</a>
						@endcan
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('customerorderexport') }}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="order_id" class="col-form-label text-md-right">{{ __('Order ID') }}</label>
									<input id="order_id" type="text" class="form-control" name="order_id" value="{{ old('order_id') }}"  autocomplete="order_id" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="order_item_id" class="col-form-label text-md-right">{{ __('Order Item ID') }}</label>
									<input id="order_item_id" type="text" class="form-control" name="order_item_id" value="{{ old('order_item_id') }}"  autocomplete="order_item_id" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="purchase_date" class="col-form-label text-md-right">{{ __('Purchase Date') }}</label>
									<input id="purchase_date" type="date" class="form-control" name="purchase_date" value="{{ old('purchase_date') }}"  autocomplete="purchase_date" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="payments_date" class="col-form-label text-md-right">{{ __('Payment Date') }}</label>
									<input id="payments_date" type="date" class="form-control" name="payments_date" value="{{ old('payments_date') }}"  autocomplete="payments_date" >
								</div>
							</div>
						</div>
						
						<div class="row">
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="reporting_date" class="col-form-label text-md-right">{{ __('Reporting Date') }}</label>
									<input id="reporting_date" type="date" class="form-control" name="reporting_date" value="{{ old('reporting_date') }}"  autocomplete="reporting_date" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="promise_date" class="col-form-label text-md-right">{{ __('Promise Date') }}</label>
									<input id="promise_date" type="date" class="form-control" name="promise_date" value="{{ old('promise_date') }}"  autocomplete="promise_date" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="buyer_name" class="col-form-label text-md-right">{{ __('Buyer Name') }}</label>
									<input id="buyer_name" type="text" class="form-control" name="buyer_name" value="{{ old('buyer_name') }}"  autocomplete="buyer_name" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="buyer_phone_number" class="col-form-label text-md-right">{{ __('Buyer Phone Number') }}</label>
									<input id="buyer_phone_number" type="text" class="form-control" name="buyer_phone_number" value="{{ old('buyer_phone_number') }}"  autocomplete="buyer_phone_number" >
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_name" class="col-form-label text-md-right">{{ __('Product Name') }}</label>
									<input id="product_name" type="text" class="form-control" name="product_name" value="{{ old('product_name') }}"  autocomplete="product_name" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="sku" class="col-form-label text-md-right">{{ __('SKU') }}</label>
									<input id="sku" type="text" class="form-control" name="sku" value="{{ old('sku') }}"  autocomplete="sku" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sku" class="col-form-label text-md-right">{{ __('Status') }}</label>
									<select id="status" name="status" class="form-control">
									<option value="Pending">Pending</option>
									<option value="Pending">Shipped</option>
									</select>
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
@section('footer-script')
<script src="{{ asset('js/customerorder.js') }}" defer></script>
@endsection