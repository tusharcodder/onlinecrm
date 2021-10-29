@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Add New GST Slabs') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('gstslab.index') }}"> Back to list</a>
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
					
					<form method="POST" action="{{ route('gstslab.store') }}" id="gstslabform">
					@csrf
						<div class="row">											
							<div class="col-md-4">
								<div class="form-group">
									<label for="amountfrom" class="col-form-label text-md-right">{{ __('Amount from*') }}</label>
									<input id="amountfrom" type="number" autofocus step="any" class="form-control" name="amountfrom" value="{{old('amountfrom') }}" required autocomplete="off" />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="amountto" class="col-form-label text-md-right">{{ __('Amount To*') }}</label>
									<input id="amountto" type="number" step="any" class="form-control" name="amountto" value="{{old('amountto') }}"  required autocomplete="off" />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="gstper" class="col-form-label text-md-right">{{ __('GST Per*') }}</label>
									<input id="gstper" type="number" step="any" class="form-control" name="gstper" value="{{old('gstper') }}" required autocomplete="off"/>
								</div>
							</div>
							
						</div>
						
						<div class="form-group row mb-0">
                            <div class="col-md-12">
								<button type="submit" id="save_sale" class="btn btn-primary">
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
<script src="{{ asset('js/sale.js') }}" defer></script>
@endsection