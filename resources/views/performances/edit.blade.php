@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create Performance') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('performances.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('performances.update', $performance->id) }}">
					@csrf
					@method('PATCH')
						
						<div class="form-group row">
							<label for="product_code" class="col-md-4 col-form-label text-md-right">{{ __('Product code*') }}</label>

							<div class="col-md-6">
								<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code', $performance->product_code) }}"  autocomplete="product_code" required>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="category" class="col-md-4 col-form-label text-md-right">{{ __('Category*') }}</label>
							<div class="col-md-6">
								<select class="form-control" id="category" name="category" required>
									<option value="">-- Select --</option>
									@foreach ($type as $key => $val)
										<option value="{{ $val }}" {{ $val == old('type',$performance->category) ? 'selected' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="salethrough" class="col-md-4 col-form-label text-md-right">{{ __('Sale Through(%)*') }}</label>

							<div class="col-md-6">
								<input id="salethrough" type="number" class="form-control" name="salethrough" value="{{ old('salethrough', $performance->sale_through) }}" step="any" min="0" max="100" autocomplete="salethrough" required>
							</div>
						</div>
						
						<div class="row">
							<label for="" class="col-md-4 col-form-label text-md-right"></label>
							<div class="col-md-6">
								<strong>Note:</strong><br/>
								Fast for value >= sale throgugh%<br/>
								Slow for value <= sale throgugh%<br/>
								Medium for value > Slow and value < Fast<br/>
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
<script src="{{ asset('js/performance.js') }}" defer></script>
@endsection