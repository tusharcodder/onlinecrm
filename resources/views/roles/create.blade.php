@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Create New Role') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('roles.store') }}">
					@csrf
						<div class="form-group row">
							<label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name*') }}</label>

							<div class="col-md-8">
								<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus required>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Permission*') }}</label>

							<div class="col-md-8">
								@foreach($permission as $key=>$value)
									<div class="form-check form-check-inline permission-check">
										<input class="form-check-input" name="permission[]" type="checkbox" id="inlineCheckbox{{$key}}" value="{{$value->id}}" {{ ( is_array( old('permission')) && in_array($value->id, old('permission') ) ) ? 'checked' : '' }}>
										<label class="form-check-label" for="inlineCheckbox{{$key}}">{{ $value->name }}</label>
									</div>
								@endforeach
							</div>
						</div>

						<div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
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