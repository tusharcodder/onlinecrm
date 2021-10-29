@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Performance') }}
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
					
					<div class="alert alert-danger" id="errorlist" style="display:none;">
					</div>
		
					<form method="POST" action="{{ route('performances.store') }}" id="performanceform">
					@csrf
					
						<div class="row">
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_code" class="col-form-label text-md-right">{{ __('Product code*') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code') }}"  autocomplete="product_code">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="category" class="col-form-label text-md-right">{{ __('Category*') }}</label>
									<select class="form-control" id="category" name="category">
										<option value="">-- Select --</option>
										@foreach ($cat as $key => $val)
											<option value="{{ $val }}" {{ $val == old('cat') ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="salethrough" class="col-form-label text-md-right">{{ __('Sale Through(%)*') }}</label>
									<input id="salethrough" type="number" class="form-control" name="salethrough" value="{{ old('salethrough') }}" step="any" min="0" max="100" autocomplete="salethrough" >
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<strong>Note:</strong><br/>
								Fast for value >= sale throgugh%<br/>
								Slow for value <= sale throgugh%<br/>
								Medium for value > Slow and value < Fast<br/>
							</div>
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="addperformance">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
						
						<div class="form-group row mb-0">
                            <div class="col-md-12">
                               <div class="table-responsive">
									<table class="table table-hover" id="performancelist">
										<thead>
											<tr>
												<th>Product Code</th>
												<th>Category</th>
												<th>Sale Through(%)</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="4">No data added.</td>
											</tr>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
						
						<div class="form-group row mb-0">
                            <div class="col-md-12">
								<input type="hidden" name="rowitem" id="rowitem"/>
								<button type="submit" id="save_performance" class="btn btn-primary" style="display:none;">
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