@extends('layouts.content')
@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Shipment Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					@can('download-shipment-report')
						<form action="{{ route('downloadshipmentreport') }}" method="POST">
						@csrf
							<div class="form-group row">
								<div class="col-md-2">
									<label for="exporttype" class="">{{ __('File Type*') }}</label> 
									<select class="form-control" id="exporttype" name="exporttype">
										<option value="csv">CSV</option>
										<option value="xls">XLS</option>
										<option value="xlsx">XLSX</option>
									</select>
								</div>
								<div class="col-md-1">
									<input type="hidden" value="" id="formval" name="formval">
									<button type="submit" class="btn btn-primary" style="margin-top: 30px !important;" id="downloadreport">
										{{ __('Download') }}
									</button>
								</div>
							</div>
						</form>
					@endcan
					<div class="row mb-1">
						<div class="col-sm-8">	
							Showing {{($shipmentreports->currentPage()-1)* $shipmentreports->perPage()+($shipmentreports->total() ? 1:0)}} to {{($shipmentreports->currentPage()-1)*$shipmentreports->perPage()+count($shipmentreports)}}  of  {{$shipmentreports->total()}}  Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>Isbn13</th>
								<th>Sku</th>
								<th>Product_name</th>
								<th>Author</th>
								<th>Publisher</th>
								<th>Order_id</th>
								<th>Order_item_id</th>
								<th>Order_date</th>
								<th>Quantity</th>
								<th>Warehouse</th>
								<th>Name</th>
								<th>Recipent_name</th>
								<th>Phone_number</th>
								<th>Add1</th>
								<th>Add2</th>
								<th>Add3</th>
								<th>City</th>
								<th>State</th>
								<th>Postal_code</th>
								<th>Country</th>
								<th>MarPla_acc</th>
								<th>Ship_type</th>
								<th>Listing_wgt</th>
							</tr>
							@if($shipmentreports->total() > 0)
								@foreach ($shipmentreports as $key => $shipment)
								<tr>
									<td>{{ ($shipmentreports->currentPage()-1) * $shipmentreports->perPage() + $loop->index + 1 }}</td>
									<td>{{ $shipment->isbnno}}</td>
									<td>{{ $shipment->sku}}</td>
									<td>{{ $shipment->proname}}</td>
									<td>{{ $shipment->author}}</td>
									<td>{{ $shipment->publisher}}</td>
									<td>{{ $shipment->order_id}}</td>
									<td>{{ $shipment->order_item_id}}</td>
									<td> {{ \Carbon\Carbon::parse($shipment->purchase_date)->format('d-m-Y')}}</td>
									<td>{{ $shipment->shipedqty }}</td>
									<td>{{ $shipment->warename}}</td>
									<td>{{ $shipment->buyer_name}}</td>
									<td>{{ $shipment->recipient_name }}</td>
									<td>{{ $shipment->buyer_phone_number }}</td>
									<td>{{ $shipment->ship_address_1}}</td>
									<td>{{ $shipment->ship_address_2}}</td>
									<td>{{ $shipment->ship_address_3 }}</td>
									<td>{{ $shipment->ship_city }}</td>
									<td>{{ $shipment->ship_state }}</td>
									<td>{{ $shipment->ship_postal_code }}</td>
									<td>{{ $shipment->ship_country }}</td>
									<td>{{ $shipment->markname }}</td>
									<td>{{ $shipment->ship_service_level }}</td>
									<td>{{ $shipment->pkg_wght }}</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="24">No records found.</td></tr>
							@endif
						</table>
						{{ $shipmentreports->links() }}
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection