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
						{{-- @can('shipment-track-import')
							<a class="btn btn-secondary btn-sm" href="{{ route('shipment-track-import') }}">Shipment Track import</a>
						@endcan --}}
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
						
					</form>
					@endcan
					@can('download-shipment-label')
					<form action="{{ route('downloadshipmentlabel') }}" method="POST">
						@csrf
						
							<div class="col-md-12">
								<button type="submit" class="btn btn-info" style="margin-top: 30px !important;" id="downloadlabel">
									{{ __('Download labels') }}
								</button>
							</div>
						
					</form>
					@endcan
					</div>
					<div class="row mb-1">
						<div class="col-sm-8">
							Showing {{($shipmentreports->currentPage()-1)* $shipmentreports->perPage()+($shipmentreports->total() ? 1:0)}} to {{($shipmentreports->currentPage()-1)*$shipmentreports->perPage()+count($shipmentreports)}} of {{$shipmentreports->total()}} Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>isbn13</th>
								<th>bisbn</th>
								<th>sku</th>
								<th>Product_name</th>
								<th>Author</th>
								<th>Publisher</th>
								<th>order_id</th>
								<th>order_item_id</th>
								<th>order_date</th>
								<th>quantity</th>
								<th>Warehouse_id</th>
								<th>Warehouse</th>
								<th>Warehouse_country</th>
								<th>rack_details</th>
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
								<th>Wght</th>
								<th>Ounce</th>
								<th>Mrp</th>
								<th>Track No</th>
								<th>Download Label</th>
							</tr>
							@if($shipmentreports->total() > 0)
							@foreach ($shipmentreports as $key => $shipment)
							<tr>
								<td>{{ ($shipmentreports->currentPage()-1) * $shipmentreports->perPage() + $loop->index + 1 }}</td>
								<td>{{ $shipment->isbnno}}</td>
								<td>{{ $shipment->bisbnno}}</td>
								<td>{{ $shipment->sku}}</td>
								<td>{{ $shipment->proname}}</td>
								<td>{{ $shipment->author}}</td>
								<td>{{ $shipment->publisher}}</td>
								<td>{{ $shipment->order_id}}</td>
								<td>{{ $shipment->order_item_id}}</td>
								<td> {{ \Carbon\Carbon::parse($shipment->purchase_date)->format('d-m-Y')}}</td>
								<td>{{ $shipment->shipedqty }}</td>
								<td>{{ $shipment->ware_id}}</td>
								<td>{{ $shipment->warename}}</td>
								<td>{{ $shipment->wccode}}</td>
								<td>{{ $shipment->rack_details}}</td>
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
								<td>{{ $shipment->wght }}</td>
								<td>{{ $shipment->ounce }}</td>
								<td>{{ $shipment->mrp }}</td>
								<td>{{ $shipment->tracking_number }}</td>
								<td>
									@if($shipment->label_pdf_url != '')
										<a href="{{$shipment->label_pdf_url}}" target="_blank" title="Download" download><img src="{{asset('images/pdficon.png')}}" width=50 height=50/></a> 
									@endif
								</td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="31">No records found.</td>
							</tr>
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