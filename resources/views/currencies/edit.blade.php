@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Currencies*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('currencies.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('currencies.update',$currencies->id) }}">
					@csrf
					@method('PATCH')
						<div class="row">							
							<div class="col-md-4">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Name*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name',$currencies->name) }}"  autocomplete="name" required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="symbol" class="col-form-label text-md-right">{{ __('Symbol*') }}</label>
									<input id="symbol" type="symbol" class="form-control" name="symbol" value="{{ old('symbol',$currencies->symbol) }}"  autocomplete="symbol" required>
								</div>
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