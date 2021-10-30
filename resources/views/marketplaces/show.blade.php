@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Market Place Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('marketplaces.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Name:</strong><br/>
								{{ $marketplace->name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Phone number:</strong><br/>
								{{ $marketplace->number }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Email:</strong><br/>
								{{ $marketplace->email }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Address:</strong><br/>
								{{ $marketplace->address }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection