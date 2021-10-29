@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Sale Management') }}
					</div>
					<div class="float-right">
						@can('sale-create')
							<a class="btn btn-success btn-sm" href="{{ route('sales.create') }}"> Add New Sale</a>
						@endcan
						@can('sale-import-export')
							<a class="btn btn-dark btn-sm" href="{{ route('sale-import-export') }}"> Sale import/export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('sales.index') }}"> Reset Search</a>
						@endif
					</div>
					<div class="clearfix"></div>
				</div>
                <div class="card-body">
					@if ($message = Session::get('success'))
						<div class="alert alert-success">
							<p>{{ $message }}</p>
						</div>
					@endif
					@if ($errormessage = Session::get('error'))
						<div class="alert alert-danger">
							<p>{{ $errormessage }}</p>
						</div>
					@endif
					<div class="row mb-1">
						<div class="col-sm-8">
							@can('sale-delete')
								<form method="POST" action="{{ route('deletesaleall') }}" style="display:inline">
									@csrf
									@method('DELETE')
									<input type="hidden" id="selectedval" name="selectedval">
									<button type="submit" class="btn btn-primary btn-sm delete_all">
										{{ __('Delete All Selected') }}
									</button>
								</form>
							@endcan 
							Showing {{($sales->currentPage()-1)* $sales->perPage()+($sales->total() ? 1:0)}} to {{($sales->currentPage()-1)*$sales->perPage()+count($sales)}}  of  {{$sales->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('sales.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search sales" value="{{ $search }}"> <span class="input-group-btn">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-search"></i>
										</button>
									</span>
								</div>
							</form>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
						<thead>
							<tr>
								@can('sale-delete')
									<th><input type="checkbox" id="master"></th>
								@endcan
								<th>No</th>
								<th>Date</th>
								<th>Invoice no</th>
								<th>PO no</th>
								<th>Brand</th>
								<th>Category</th>
								<th>Ven Type</th>
								<th>Ven name</th>
								<th>Agg ven name</th>
								<th>State</th>
								<th>Colour</th>
								<th>Size</th>
								<th>Product code</th>
								<th>Qty</th>
								<th>Image</th>
								<th width="378px">Action</th>
							</tr>
							</thead>
							<tbody>
							@if($sales->total() > 0)
								
								@foreach ($sales as $key => $sale)
								<tr>
									@can('sale-delete')
										<td><input type="checkbox" class="sub_chk" data-id="{{$sale->id}}"></td>
									@endcan
									<td>{{ ($sales->currentPage()-1) * $sales->perPage() + $loop->index + 1 }}</td>
									<td> {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y')}}</td>
									<td>{{ $sale->invoice_no }}</td>
									<td>{{ $sale->po_no }}</td>
									<td>{{ $sale->brand }}</td>
									<td>{{ $sale->category }}</td>
									<td>{{ $sale->vendor_type }}</td>
									<td>{{ $sale->vendor_name }}</td>
									<td>{{ $sale->aggregator_vendor_name }}</td>
									<td>{{ $sale->state }}</td>
									<td>{{ $sale->colour}}</td>
									<td>{{ $sale->size }}</td>
									<td>{{ $sale->product_code }}</td>
									<td>{{ $sale->quantity }}</td>
									<td>
										@if(!empty($sale->img_url))
										<img src="{{ asset($sale->img_url) }}" width="80px" height="50px">
										@endif
									</td>
									<td>
										<a class="btn btn-info btn-sm" title="Show" href="{{ route('sales.show',$sale->id) }}">Show</a>										
										@can('sale-edit')
											<a class="btn btn-primary btn-sm" title="Edit" href="{{ route('sales.edit',$sale->id) }}">Edit</a>
										@endcan
										@can('sale-delete')
											<form method="POST" action="{{ route('sales.destroy',$sale->id) }}" style="display:inline">
												@csrf
												@method('DELETE')
												<button type="submit" title="Delete" class="btn btn-danger btn-sm">
												{{ __('Delete') }}
												</button>
											</form>
										@endcan
									</td>
								</tr>
								@endforeach
							@else
								@can('sale-delete')
									<tr><td colspan="17">No records found.</td></tr>
								@else
									<tr><td colspan="16">No records found.</td></tr>
								@endcan
							@endif
							</tbody>
						</table>
						{{ $sales->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/sale.js') }}" defer></script>
@endsection