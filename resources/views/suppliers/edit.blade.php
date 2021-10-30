@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Supplier*') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('suppliers.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('suppliers.update',$supplier->id) }}">
					@csrf
					@method('PATCH')
						<div class="row">				
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Supplier name*') }}</label>
									<input id="name" type="text" class="form-control" name="name" value="{{ old('name',$supplier->name) }}"  autocomplete="name" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="phone" class="col-form-label text-md-right">{{ __('Phone number') }}</label>
									<input id="phone" type="number" class="form-control" name="phone" value="{{ old('phone',$supplier->number) }}"  autocomplete="phone" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="email" class="col-form-label text-md-right">{{ __('Email') }}</label>
									<input id="email" type="email" class="form-control" name="email" value="{{ old('email',$supplier->email) }}"  autocomplete="email">
								</div>
							</div>
						</div>						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="address" class="col-form-label text-md-right">{{ __('Address') }}</label>
									<textarea id="address" class="form-control" name="address" rows="4" cols="50">{{ old('address',$supplier->address) }}</textarea>
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