@extends('layouts.content')
@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Multi Packaging Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					@can('download-multi-packaging-report')
						<form action="{{ route('downloadmultipackagingreport') }}" method="POST">
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
							Showing {{($multipackreports->currentPage()-1)* $multipackreports->perPage()+($multipackreports->total() ? 1:0)}} to {{($multipackreports->currentPage()-1)*$multipackreports->perPage()+count($multipackreports)}}  of  {{$multipackreports->total()}}  Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>Isbn 13</th>
								<th>Sku</th>
								<th>Title</th>
								<th>Order ID</th>
								<th>Order Item ID</th>
								<th>Order Date</th>
								<th>Quantity</th>
								<th>Country</th>
								<th>Location</th>
								<th>Label No</th>
							</tr>
							@if($multipackreports->total() > 0)
								@foreach ($multipackreports as $key => $multipack)
								
								@php
									$multipack->shipingqty = empty($multipack->shipingqty) ? 0 : $multipack->shipingqty;
								@endphp
								
								<tr>
									<td>{{ ($multipackreports->currentPage()-1) * $multipackreports->perPage() + $loop->index + 1 }}</td>
									<td>{{ $multipack->isbnno}}</td>
									<td>{{ $multipack->sku}}</td>
									<td>{{ $multipack->bookname}}</td>
									<td>{{ $multipack->order_id}}</td>
									<td>{{ $multipack->order_item_id}}</td>
									<td>{{ \Carbon\Carbon::parse($multipack->purchase_date)->format('d-m-Y')}}</td>
									<td>{{ $multipack->shipingqty}}</td>
									<td>{{ $multipack->ship_country}}</td>
									<td></td>
									<td></td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="11">No records found.</td></tr>
							@endif
						</table>
						{{ $multipackreports->links() }}
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection