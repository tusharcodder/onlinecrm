@extends('layouts.content')
@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Stock Pull Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					@can('download-stock-pull-report')
						<form action="{{ route('downloadstockpullreport') }}" method="POST">
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
							Showing {{($stockpullreports->currentPage()-1)* $stockpullreports->perPage()+($stockpullreports->total() ? 1:0)}} to {{($stockpullreports->currentPage()-1)*$stockpullreports->perPage()+count($stockpullreports)}}  of  {{$stockpullreports->total()}}  Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>Isbn 13</th>
								<th>Title</th>
								<th>Stock</th>
								<th>Quantity</th>
							</tr>
							@if($stockpullreports->total() > 0)
								@foreach ($stockpullreports as $key => $stockpull)
								@php
									$stockpull->purqty = empty($stockpull->purqty) ? 0 : $stockpull->purqty;
									$stockpull->shiped_qty = empty($stockpull->shiped_qty) ? 0 : $stockpull->shiped_qty;
									$stockpull->shipingqty = empty($stockpull->shipingqty) ? 0 : $stockpull->shipingqty;
									$actualstock = $stockpull->purqty - $stockpull->shiped_qty;
									$actualstock = empty($actualstock) ? 0 : $actualstock;
								@endphp
								<tr>
									<td>{{ ($stockpullreports->currentPage()-1) * $stockpullreports->perPage() + $loop->index + 1 }}</td>
									<td>{{ $stockpull->isbnno }}</td>
									<td>{{ $stockpull->bookname }}</td>
									<td>{{ $actualstock }}</td>
									<td>{{ $stockpull->shipingqty }}</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="5">No records found.</td></tr>
							@endif
						</table>
						{{ $stockpullreports->links() }}
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection