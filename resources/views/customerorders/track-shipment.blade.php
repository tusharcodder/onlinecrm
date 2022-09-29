@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Tracking Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('customerorders.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Tracking Number:</strong><br/>
								{{ $id }}
							</div>
						</div>
						@if(count($customerorders) > 0)  
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Tracking Status:</strong><br/>
								{{ $customerorders[0]->tracking_status }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Tracking Response:</strong><br/>
								<pre>
									<code>
									@php
										echo json_encode(json_decode($customerorders[0]->tracking_api_response), JSON_PRETTY_PRINT);
									@endphp
									</code>	
								</pre>
							</div>
						</div>
						@else
							<h3>No details updated!!!</h3>.
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection