@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Zone Price') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('uspszoneprice.index') }}"> Back to list</a>
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
		
					<form method="POST" action="{{ route('uspszoneprice.update',$zone->id) }}">
                    @csrf 
					@method('PATCH')					
						<div class="row">
                        <div class="col-md-3">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Zone List*') }}</label>
									<select name="zonelist" class="form-control" id="zonelist" required>
                                    <option value="">--Select--</option>
                                        @foreach($zonelist as $key => $val)
                                            <option value="{{$val->id}}" {{ $val->id == $zone->zone_id ? 'selected' : '' }}>{{$val->zone}}</option>
                                        @endforeach 
                                    </select>    
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="symbol" class="col-form-label text-md-right">{{ __('Weight From(lbs)*') }}</label>
									<input id="symbol" type="number"  step="any" class="form-control" name="wgt_lbs_from" value="{{ old('wgt_lbs_from',$zone->lbs_wgt_from) }}"  autocomplete="wgt_lbs_from" required>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="symbol" class="col-form-label text-md-right">{{ __('Weight To(lbs)*') }}</label>
									<input id="symbol" type="number"  step="any" class="form-control" name="wgt_lbs_to" value="{{ old('wgt_lbs_to',$zone->lbs_wgt_to) }}"  autocomplete="wgt_lbs_to" required>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="name" class="col-form-label text-md-right">{{ __('Zone Price*') }}</label>
									<input id="name" type="number" step="any" class="form-control" name="zone_price" value="{{ old('zone_price',$zone->zone_price) }}"  autocomplete="zone_price" required>
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