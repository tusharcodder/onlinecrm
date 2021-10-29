@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Vendor Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('vendorss.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Type:</strong><br/>
								{{ $vendor->type }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Vendor name:</strong><br/>
								{{ $vendor->vendor_name }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Contact person name:</strong><br/>
								{{ $vendor->contact_person_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Contact person email:</strong><br/>
								{{ $vendor->contact_person_email }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Contact person number:</strong><br/>
								{{ $vendor->contact_person_number }}
							</div>
						</div>
					</div>
						
					<div class="row">	
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Commission type:</strong><br/>
								{{ $vendor->commission_type }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Commission%:</strong><br/>
								{{ $vendor->commission }}%
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								@if(!$aggregatordetails->isEmpty())
									<strong>Aggregator vendor info:</strong>
									<div class="table-responsive">
										<table class="table table-hover" id="dynamicTable">  
											<tr>
												<th>Name</th>
												<th>Commission%</th>
											</tr>
											@foreach($aggregatordetails as $v)
											<tr>
												<td>{{ $v->aggregator_vendor_name }}</td>  
												<td>{{ $v->aggregator_vendor_commission }}%</td>   
											</tr>
											@endforeach
										</table>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection