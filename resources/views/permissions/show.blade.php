@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Permissions Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Name:</strong>
								{{ $permission->name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								@if(!$rolePermissions->isEmpty())
									<strong>Roles:</strong>
									@foreach($rolePermissions as $v)
										<label class="label label-success">{{ $v->name }},</label>
									@endforeach
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								@if(!$userPermissions->isEmpty())
									<strong>Users:</strong>
									@foreach($userPermissions as $v)
										<label class="label label-success">{{ $v->name }},</label>
									@endforeach
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