@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Purchase Order Management') }}
					</div>
					<div class="float-right">
						@can('purchase-import-export')
							<a class="btn btn-secondary btn-sm" href="{{ route('purchase-order-import-export') }}">Purchase Order import/export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('purchaseorders.index') }}"> Reset Search</a>
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
							Showing {{($purchaseorders->currentPage()-1)* $purchaseorders->perPage()+($purchaseorders->total() ? 1:0)}} to {{($purchaseorders->currentPage()-1)*$purchaseorders->perPage()+count($purchaseorders)}}  of  {{$purchaseorders->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('purchaseorders.index') }}" role="search">
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
								<th>Bill No</th>
								<th>ISBN13</th>		
                                <th>Vendor</th>														
								<th>Book Title</th>								
                                <th>Quantity</th>
                                <th>MRP</th>
                                <th>Disc(%)</th>
                                <th>Cost Price</th>
                                <th>Purchase By</th>
								<th>Purchase Date</th>
                                <th>Action</th>
							</tr>
							@if($purchaseorders->total() > 0)                          
								@foreach ($purchaseorders as $key => $purchaseorder)
								<tr>
									<td>{{ ($purchaseorders->currentPage()-1) * $purchaseorders->perPage() + $loop->index + 1 }}</td>
                                   
									<td>{{ $purchaseorder->bill_no }}</td>									
                                    <td>{{ $purchaseorder->isbn13}}</td>
									<td>{{ $purchaseorder->vendor }}</td>	
                                    <td>{{ $purchaseorder->name}}</td>
                                    <td>{{ $purchaseorder->quantity}}</td>
									<td>{{ $purchaseorder->mrp }}</td>
									<td>{{ $purchaseorder->discount }}</td>
                                    <td>{{ $purchaseorder->cost_price }}</td>
                                    <td>{{ $purchaseorder->purchase_by }}</td>
									<td>{{ \Carbon\Carbon::parse($purchaseorder->purchase_date)->format('d-m-Y')}}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('purchaseorders.show',$purchaseorder->id) }}">Show</a>									
									</td>
								</tr>                               
								@endforeach
							@else
								<tr><td colspan="12">No records found.</td></tr>
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