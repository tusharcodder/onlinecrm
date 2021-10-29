@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Manufacturer Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('buyers.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Name:</strong><br/>
								{{ $buyer->name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Country:</strong><br/>
								{{ $buyer->country }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Address:</strong><br/>
								{{ $buyer->address }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								@if(!$buyercontactperdetails->isEmpty())
									<strong>Contact person info:</strong>
									<div class="table-responsive">
										<table class="table table-hover" id="dynamicTable">  
											<tr>
												<th>Name</th>
												<th>Email ID</th>
												<th>Phone number</th>
											</tr>
											@foreach($buyercontactperdetails as $v)
											<tr>
												<td>{{ $v->contact_person_name }}</td>  
												<td>{{ $v->contact_person_email }}</td>  
												<td>{{ $v->contact_person_number }}</td>  
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