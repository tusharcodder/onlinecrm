@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create Discount') }}
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
		
					<form method="POST" action="{{ route('discounts.update', $discount->id) }}">
					@csrf
					@method('PATCH')
						<div class="form-group row">
							<label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type*') }}</label>

							<div class="col-md-6">
								<select class="form-control" id="type" name="type" autofocus required>
								<option value="">-- Select --</option>
								@foreach ($type as $key => $val)
									<option value="{{ $val }}" {{ $val == old('type',$discount->vendor_type) ? 'selected' : '' }}>{{ $val }}</option>
								@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="vendor" class="col-md-4 col-form-label text-md-right">{{ __('Vendors*') }}</label>
							<input type="hidden" value="{{old('vendor',$discount->vendor_name)}}" id="selvendor" name="selvendor">
							<div class="col-md-6">
								<select class="form-control" id="vendor" name="vendor" required>
									<option value="">-- Select --</option>
								</select>
							</div>
						</div>
						
						<div class="form-group row aggregatorcontainer" style="display:none;">
							<label for="aggregator_vendor" class="col-md-4 col-form-label text-md-right">{{ __('Aggregator vendor*') }}</label>
							<input type="hidden" value="{{old('aggregator_vendor',$discount->aggregator_vendor_name)}}" name="selaggvendor" id="selaggvendor">
							<div class="col-md-6">
								<select class="form-control" id="aggregator_vendor" name="aggregator_vendor">
									<option value="">-- Select --</option>
								</select>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="product_code" class="col-md-4 col-form-label text-md-right">{{ __('Product code*') }}</label>

							<div class="col-md-6">
								<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code', $discount->product_code) }}"  autocomplete="product_code" required>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="discount" class="col-md-4 col-form-label text-md-right">{{ __('Discount*') }}</label>

							<div class="col-md-6">
								<input id="discount" type="number" class="form-control" name="discount" value="{{ old('discount', $discount->discount) }}" step="any" min="0" max="100" autocomplete="discount" required>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="valid_from_date" class="col-md-4 col-form-label text-md-right">{{ __('Valid from date*') }}</label>

							<div class="col-md-6">
								<input id="valid_from_date" type="datetime-local" class="form-control" name="valid_from_date" value="{{ old('valid_from_date', $discount->valid_from_date) }}"  autocomplete="valid_from_date" required>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="valid_to_date" class="col-md-4 col-form-label text-md-right">{{ __('Valid to date*') }}</label>

							<div class="col-md-6">
								<input id="valid_to_date" type="datetime-local" class="form-control" name="valid_to_date" value="{{ old('valid_to_date', $discount->valid_to_date) }}"  autocomplete="valid_to_date" required>
							</div>
						</div>
						
						<div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
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