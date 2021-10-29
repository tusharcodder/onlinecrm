@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Add Stock Threshold') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('stocks.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
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
					<form method="POST" action="{{ route('stockthreshold.store') }}" id="stockform">
					@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="low_stock_threshold" class="col-form-label text-md-right">{{ __('Low stock threshold*') }}</label>
									<input id="low_stock_threshold" type="number" class="form-control" name="low_stock_threshold" value="{{ old('low_stock_threshold', $thresholdval['low_stock_threshold']) }}"  autocomplete="low_stock_threshold" step="Any" min="0" max="100" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="out_of_stock_threshold" class="col-form-label text-md-right">{{ __('Out of stock threshold*') }}</label>
									<input id="out_of_stock_threshold" type="number" class="form-control" name="out_of_stock_threshold" value="{{ old('out_of_stock_threshold', $thresholdval['out_of_stock_threshold']) }}"  autocomplete="out_of_stock_threshold" step="Any" min="0" max="100" required />
								</div>
							</div>
						</div>						
					
						<div class="form-group row mb-0">
                            <div class="col-md-12">
								<button type="submit" id="save" class="btn btn-primary">
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
<script src="{{ asset('js/stock.js') }}" defer></script>
@endsection