@extends('layouts.content')
@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('List') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					@can('download-purchase-report')
						<form action="{{route('downloadpurchasereport')}}" method="POST">
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
							Showing {{($purchaseorders->currentPage()-1)* $purchaseorders->perPage()+($purchaseorders->total() ? 1:0)}} to {{($purchaseorders->currentPage()-1)*$purchaseorders->perPage()+count($purchaseorders)}}  of  {{$purchaseorders->total()}}  Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>Isbn 13</th>
								<th>Book Name</th>
								<th>Author</th>
								<th>Publisher</th>
								<th>Quantity</th>
								<th>Vendor Name</th>								
							</tr>
							@if($purchaseorders->total() > 0)
								@foreach ($purchaseorders as $key => $purchaseorder)
								<tr>
									<td>{{ ($purchaseorders->currentPage()-1) * $purchaseorders->perPage() + $loop->index + 1 }}</td>
									<td>{{ $purchaseorder['isbn13']}}</td>
									<td>{{ $purchaseorder['book']}}</td>
									<td>{{ $purchaseorder['author']}}</td>
									<td>{{ $purchaseorder['publisher']}}</td>
									<td>{{ $purchaseorder['quantity']}}</td>
									<td>{{ $purchaseorder['vendor_name']}}</td>								
								</tr>
								@endforeach
							@else
								<tr><td colspan="7">No records found.</td></tr>
							@endif
						</table>
						{{ $purchaseorders->links() }}
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection