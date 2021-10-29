@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Vendor*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('vendorss.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('vendorss.update',$vendor->id) }}">
					@csrf
					@method('PATCH')
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Type*') }}</label>
									<select class="form-control" id="type" name="type" autofocus required>
										<option value="">-- Select --</option>
										@foreach ($type as $key => $val)
											<option value="{{ $val }}" {{ $val == old('type',$vendor->type) ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name*') }}</label>
									<input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name',$vendor->vendor_name) }}"  autocomplete="vendor_name" required>
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="cname" class="col-form-label text-md-right">{{ __('Conatct person name*') }}</label>
									<input id="cname" type="text" class="form-control" name="cname" value="{{ old('cname',$vendor->contact_person_name) }}"  autocomplete="cname" required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="cemail" class="col-form-label text-md-right">{{ __('Conatct person email*') }}</label>
									<input id="cemail" type="email" class="form-control" name="cemail" value="{{ old('cemail',$vendor->contact_person_email) }}"  autocomplete="cemail" multiple required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="cphone" class="col-form-label text-md-right">{{ __('Conatct person phone number*') }}</label>
									<input id="cphone" type="number" class="form-control" name="cphone" value="{{ old('cphone',$vendor->contact_person_number) }}"  autocomplete="cphone" required>
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="ctype" class="col-form-label text-md-right">{{ __('Commsion Type*') }}</label>
									<select class="form-control" id="ctype" name="ctype" required>
										<option value="">-- Select --</option>
										<option value="Commsion Based"  {{ "Commsion Based" == old('type', $vendor->commission_type) ? 'selected' : '' }} >Commsion Based</option>
										<option value="NOT Based"  {{ "NOT Based" == old('type',$vendor->commission_type) ? 'selected' : '' }}>NOT Based</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="commission" class="col-form-label text-md-right">{{ __('Commsion(%)*') }}</label>
									<input id="commission" type="number" class="form-control" name="commission" value="{{ old('commission',$vendor->commission) }}" step="any" autocomplete="commission" min="0" max="100" required>
								</div>
							</div>

						</div>
						
						<div class="row addmorecontainer" style={{ old('type', $vendor->type) != 'Aggregator' ? 'display:none' : 'display:block' }}>
							@php
								$oldValues = empty(old('addmore',$aggregatordetails)) ? [1] : old('addmore',$aggregatordetails);
							@endphp
							<div class="table-responsive">
								<table class="table table-hover" id="dynamicTable">  
									<tr>
										<th>Vendor name*</th>
										<th>Vendor commission(%)*</th>
										<th>Action</th>
									</tr>
									@foreach ($oldValues as $key=>$val)
										<tr>
											<td><input type="text" name="addmore[{{$key}}][vname]" placeholder="Enter vendor name" class="form-control" value="{{ $val['vname'] }}" /></td>
											
											<td><input type="number" name="addmore[{{$key}}][vcomm]" placeholder="Enter vendor commission(%)" class="form-control" value="{{ $val['vcomm'] }}" step="any" min="0" max="100"/></td>
											
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
<script src="{{ asset('js/vendor.js') }}" defer></script>
@endsection