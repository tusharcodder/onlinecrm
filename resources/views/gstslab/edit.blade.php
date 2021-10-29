@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit GST Slabs') }}
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
					
					<form method="POST" action="{{ route('gstslab.update',$gstslb->id) }}" id="gstslabform">
					@csrf
					@method('PATCH')
						<div class="row">											
							<div class="col-md-4">
								<div class="form-group">
									<label for="amountfrom" class="col-form-label text-md-right">{{ __('Amount from*') }}</label>
									<input id="amountfrom" type="text" class="form-control" name="amountfrom" value="{{old('amountfrom',$gstslb->amount_from) }}" required autocomplete="amountfrom" />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="amountto" class="col-form-label text-md-right">{{ __('Amount To*') }}</label>
									<input id="amountto" type="text" class="form-control" name="amountto" value="{{old('amountto',$gstslb->amount_to) }}"  required autocomplete="amountto" />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="gstper" class="col-form-label text-md-right">{{ __('GST Per*') }}</label>
									<input id="gstper" type="text" class="form-control" name="gstper" value="{{old('gstper',$gstslb->gst_per) }}" required autocomplete="gstper"/>
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