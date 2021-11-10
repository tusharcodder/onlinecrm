@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Customer Order Management') }}
					</div>
					<div class="float-right">
						@can('customer-order-import-export')
							<a class="btn btn-secondary btn-sm" href="{{ route('customer-order-import-export') }}">Customer Order import/export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('customerorders.index') }}"> Reset Search</a>
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
					<div class="row mb-1">
						<div class="col-sm-8">	
							Showing {{($customerorders->currentPage()-1)* $customerorders->perPage()+($customerorders->total() ? 1:0)}} to {{($customerorders->currentPage()-1)*$customerorders->perPage()+count($customerorders)}}  of  {{$customerorders->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('customerorders.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search" value="{{ $search }}"> <span class="input-group-btn">
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
							<tr>
								<th>#</th>
								<th>Order ID</th>
								<th>Order Item ID</th>
								<th>Purchase Date</th>
								<th>Payment Date</th>
								<th>Reporting Date</th>
                                <th>Buyer Name</th>
                                <th>Buyer Phone</th>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Quantity</th>
							</tr>
							@if($customerorders->total() > 0)                          
								@foreach ($customerorders as $key => $customerorder)
								<tr>
									<td>{{ ($customerorders->currentPage()-1) * $customerorders->perPage() + $loop->index + 1 }}</td>
									<td>{{ $customerorder->order_id }}</td>
									<td>{{ $customerorder->order_item_id }}</td>
									<td>{{ \Carbon\Carbon::parse($customerorder->purchase_date)->format('d-m-Y')}}</td>
                                    <td>{{ \Carbon\Carbon::parse($customerorder->payments_date)->format('d-m-Y')}}</td>
									<td>{{ \Carbon\Carbon::parse($customerorder->reporting_date)->format('d-m-Y')}}</td>
                                    <td>{{ $customerorder->buyer_name}}</td>
                                    <td>{{ $customerorder->buyer_phone_number}}</td>
                                    <td>{{ $customerorder->product_name}}</td>
									<td>{{ $customerorder->sku }}</td>
									<td>{{ $customerorder->quantity_purchased }}</td>
								</tr>                               
								@endforeach
							@else
								<tr><td colspan="11">No records found.</td></tr>
							@endif
						</table>
						{{ $customerorders->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection