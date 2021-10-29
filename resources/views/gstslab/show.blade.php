@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show GST Slabs') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('gstslab.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Amount From: </strong>
								{{ $gstslb->amount_from }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Amount To: </strong>
								{{ $gstslb->amount_to }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>GST: </strong>
								{{ $gstslb->gst_per.'(%)' }}
							</div>
						</div>
					</div>	
					
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection