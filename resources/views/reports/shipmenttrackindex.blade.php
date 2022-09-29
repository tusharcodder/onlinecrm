@extends('layouts.content')
@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Shipment Track Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					@can('download-shipment-track-report')
					<form action="{{ route('downloadshipmenttrackreport') }}" method="POST">
						@csrf
							
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="order_id" class="col-form-label text-md-right">{{ __('Order ID') }}</label>
									<input id="order_id" type="text" class="form-control" name="order_id" value="{{ old('order_id') }}"  autocomplete="order_id" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="order_item_id" class="col-form-label text-md-right">{{ __('Order Item ID') }}</label>
									<input id="order_item_id" type="text" class="form-control" name="order_item_id" value="{{ old('order_item_id') }}"  autocomplete="order_item_id" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date" class="col-form-label text-md-right">{{ __('Shipment From Date') }}</label>
									<input id="from_date" type="date" class="form-control" name="from_date" value="{{ old('from_date', date('Y-m-d')) }}" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="to_date" class="col-form-label text-md-right">{{ __('Shipment To Date') }}</label>
									<input id="to_date" type="date" class="form-control" name="to_date" value="{{ old('to_date', date('Y-m-d')) }}" >
								</div>
							</div>
							
						</div>
						
						<div class="form-group row">
							<div class="col-md-3">
								<label for="trackid" class="">{{ __('Tracking Number') }}</label>
								<input id="trackid" type="text" class="form-control" name="trackid" value="{{ old('trackid') }}" >
							</div>
							
							<div class="col-md-3">
								<label for="exporttype" class="">{{ __('File Type*') }}</label>
								<select class="form-control" id="exporttype" name="exporttype">
									<option value="csv">CSV</option>
									<option value="xls">XLS</option>
									<option value="xlsx">XLSX</option>
								</select>
							</div>
							
							<div class="col-md-3">
								<input type="hidden" value="" id="formval" name="formval">
								<button type="submit" class="btn btn-primary" style="margin-top: 30px !important;" id="downloadreport">
									{{ __('Download') }}
								</button>
							</div>
						</div>
					</form>
					@endcan
					</div>
					<div class="row mb-1">
						<div class="col-sm-8">
							Showing {{($results->currentPage()-1)* $results->perPage()+($results->total() ? 1:0)}} to {{($results->currentPage()-1)*$results->perPage()+count($results)}} of {{$results->total()}} Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>order_id</th>
								<th>order_item_id</th>
								<th>ship_date</th>
								<th>quantity</th>
								<th>carrier_code</th>
								<th>carrier_name</th>
								<th>tracking_number</th>
								<th>tracking_status</th>
								<th>ship_method</th>
							</tr>
							@if($results->total() > 0)
							@foreach ($results as $key => $shipment)
							<tr>
								<td>{{ ++$key }}</td>
								<td>{{ $shipment->order_id }}</td>
								<td>"{{ $shipment->order_item_id }}"</td>
								<td>{{ $shipment->shipment_date  }}</td>
								<td>{{ $shipment->quantity_shipped }}</td>
								<td>{{ $shipment->carrier_service }}</td>
								<td>{{ $shipment->carrier_name }}</td>
								<td>{{ $shipment->shipper_tracking_id  }}</td>
								<td>{{ $shipment->tracking_status }}</td>
								<td></td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="10">No records found.</td>
							</tr>
							@endif
						</table>
						{{ $results->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/shipment.js') }}" defer></script>
@endsection