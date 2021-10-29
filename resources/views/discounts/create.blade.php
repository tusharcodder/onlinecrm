@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Discount') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('discounts.index') }}"> Back to list</a>
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
					
					<div class="alert alert-danger" id="errorlist" style="display:none;">
					</div>
		
					<form method="POST" action="{{ route('discounts.store') }}" id="discountform">
					@csrf
					
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Type*') }}</label>
									<select class="form-control" id="type" name="type" autofocus>
										<option value="">-- Select --</option>
										@foreach ($type as $key => $val)
											<option value="{{ $val }}" {{ $val == old('type') ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor" class="col-form-label text-md-right">{{ __('Vendor*') }}</label>
									<select class="form-control" id="vendor" name="vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group aggregatorcontainer" style="display:none;">
									<label for="aggregator_vendor" class="col-form-label text-md-right">{{ __('Aggregator vendor*') }}</label>

									<select class="form-control" id="aggregator_vendor" name="aggregator_vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Product code*') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code') }}"  autocomplete="product_code" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Discount(%)*') }}</label>
									<input id="discount" type="number" class="form-control" name="discount" value="{{ old('discount') }}" step="any" min="0" max="100" autocomplete="discount" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="valid_from_date" class="col-form-label text-md-right">{{ __('Valid from date*') }}</label>
									<input id="valid_from_date" type="datetime-local" class="form-control" name="valid_from_date" value="{{ old('valid_from_date') }}"  autocomplete="valid_from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="valid_to_date" class="col-form-label text-md-right">{{ __('Valid to date*') }}</label>
									<input id="valid_to_date" type="datetime-local" class="form-control" name="valid_to_date" value="{{ old('valid_to_date') }}"  autocomplete="valid_to_date">
								</div>
							</div>
							
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="adddiscount">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
						
						<div class="form-group row mb-0">
                            <div class="col-md-12">
                               <div class="table-responsive">
									<table class="table table-hover" id="discountlist">
										<thead>
											<tr>
												<th>Type</th>
												<th>Vendor</th>
												<th>Agg_vendor</th>
												<th>Product_code</th>
												<th>Discount(%)</th>
												<th>Valid_from_date</th>
												<th>Valid_to_date</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="8">No data added.</td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
						
						<div class="form-group row mb-0">
                            <div class="col-md-12">
								<input type="hidden" name="rowitem" id="rowitem"/>
								<button type="submit" id="save_discount" class="btn btn-primary" style="display:none;">
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
<script src="{{ asset('js/discount.js') }}" defer></script>
@endsection