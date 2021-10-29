@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Manufacturer*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('buyers.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('buyers.store') }}">
					@csrf
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Name*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="country" class="col-form-label text-md-right">{{ __('Country*') }}</label>
									<input id="country" type="text" class="form-control" name="country" value="{{ old('country') }}"  autocomplete="country" required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="address" class="col-form-label text-md-right">{{ __('Address') }}</label>
									<textarea id="address" name="address" class="form-control" rows="2" cols="50" autocomplete="address">{{ old('address') }}</textarea>
								</div>
							</div>
							
						</div>
						
						<div class="row">
							@php
								$oldValues = empty(old('addmore')) ? [1] : old('addmore');
							@endphp
							<div class="table-responsive">
								<table class="table table-hover" id="dynamicTable">  
									<tr>
										<th>Contact person name*</th>
										<th>Contact person email ID</th>
										<th>Contact person phone</th>
										<th>Action</th>
									</tr>
									@foreach ($oldValues as $key=>$val)
										<tr>
											<td><input type="text" name="addmore[{{$key}}][cname]" placeholder="Enter contact person name" class="form-control" value="{{ $val['cname'] }}" required/></td>  
											<td><input type="email" name="addmore[{{$key}}][cemail]" placeholder="Enter contact person email ID" class="form-control" value="{{ $val['cemail'] }}" multiple/></td>
											<td><input type="text" name="addmore[{{$key}}][cphone]" placeholder="Enter contact person number" class="form-control" value="{{ $val['cphone'] }}"/></td>
											
											@if($key == '0')
												<td><button type="button" name="add" id="add" class="btn btn-success btn-sm">Add More</button></td> 
											@else
												<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td>
											@endif
										</tr> 
									@endforeach 
								</table>
							
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
@section('footer-script')
<script src="{{ asset('js/buyer.js') }}" defer></script>
@endsection