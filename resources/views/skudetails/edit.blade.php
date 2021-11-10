@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Sku Code Details*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('skudetails.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('skudetails.update',$skudetails->id) }}">
					@csrf
					@method('PATCH')
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="mplace" class="col-form-label text-md-right">{{ __('Market Place*') }}</label>
									<!-- <input id="mplace" type="text" class="form-control" name="mplace" value="{{ old('mplace') }}"  autocomplete="mplace" required> -->
                                    <select class="form-control" id="mplace" name="mplace" autofocus>
										<option value="">-- Select --</option>
										@foreach ($marketplaces as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == $skudetails->market_id  ? 'selected' : '' }}>{{ $val->name }}</option>
											
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="warehouse" class="col-form-label text-md-right">{{ __('Warehouse*') }}</label>
									<!-- <input id="warehouse" type="number" class="form-control" name="warehouse" value="{{ old('warehouse') }}"  autocomplete="warehouse" required> -->
                                    <select class="form-control" id="warehouse" name="warehouse" autofocus>
										<option value="">-- Select --</option>
										@foreach ($warehouses as $key => $val)
											<option value="{{ $val->id }}" {{ $val->id == $skudetails->warehouse_id ? 'selected' : '' }}>{{ $val->name }}</option>
											
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="isbn13" class="col-form-label text-md-right">{{ __('ISBN13*') }}</label>
									<input id="isbn13" type="text" class="form-control" name="isbn13" value="{{ old('isbn13',$skudetails->isbn13) }}"  autocomplete="isbn13" required>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="isbn10" class="col-form-label text-md-right">{{ __('ISBN10*') }}</label>
									<input type="text" id="isbn10" name="isbn10" class="form-control" value="{{ old('isbn10',$skudetails->isbn10) }}" autocomplete="isbn10" required />
								</div>
							</div>
                            <div class="col-md-4">
								<div class="form-group">
									<label for="sku" class="col-form-label text-md-right">{{ __('SKU*') }}</label>
									<input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku',$skudetails->sku_code) }}" autocomplete="sku" required />
								</div>                               
							</div>
                            <div class="col-md-4">
                            <div class="form-group">
									<label for="mrp" class="col-form-label text-md-right">{{ __('MRP*') }}</label>
									<input type="text" id="mrp" name="mrp" class="form-control" value="{{ old('mrp',$skudetails->mrp) }}" autocomplete="mrp" required />
								</div>
                            </div>
						</div>

                        <div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="disc" class="col-form-label text-md-right">{{ __('Disc*') }}</label>
									<input type="text" id="disc" name="disc" class="form-control" value="{{ old('disc',$skudetails->disc) }}" autocomplete="disc" required />
								</div>
							</div>
                            <div class="col-md-4">
								<div class="form-group">
									<label for="wght" class="col-form-label text-md-right">{{ __('Weight(kg)*') }}</label>
									<input type="text" id="wght" name="wght" class="form-control" value="{{ old('wght',$skudetails->wght) }}" autocomplete="wght" required />
								</div>                               
							</div>
                            <div class="col-md-4">
                            <div class="form-group">
									<label for="pgkwght" class="col-form-label text-md-right">{{ __('Pkg-weight(kg)*') }}</label>
									<input type="text" id="pgkwght" name="pgkwght" class="form-control" value="{{ old('pgkwght',$skudetails->pkg_wght) }}" autocomplete="pgkwght" required />
								</div>
                            </div>
						</div>

						<div class="form-group row mb-0">
                            <div class="col-md-12">
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