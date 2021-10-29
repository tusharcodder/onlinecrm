@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Discount Management') }}
					</div>
					<div class="float-right">
						@can('discount-create')
							<a class="btn btn-success btn-sm" href="{{ route('discounts.create') }}"> Create New Discount</a>
						@endcan
						@can('discount-import-export')
							<a class="btn btn-dark btn-sm" href="{{ route('discount-import-export') }}"> Discount import/export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('discounts.index') }}"> Reset Search</a>
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
							@can('discount-delete')
								<form method="POST" action="{{ route('deletediscountall') }}" style="display:inline">
									@csrf
									@method('DELETE')
									<input type="hidden" id="selectedval" name="selectedval">
									<button type="submit" class="btn btn-primary btn-sm delete_all">
										{{ __('Delete All Selected') }}
									</button>
								</form>
							@endcan 
							Showing {{($discounts->currentPage()-1)* $discounts->perPage()+($discounts->total() ? 1:0)}} to {{($discounts->currentPage()-1)*$discounts->perPage()+count($discounts)}}  of  {{$discounts->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('discounts.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search discounts" value="{{ $search }}"> <span class="input-group-btn">
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
								@can('discount-delete')
									<th><input type="checkbox" id="master"></th>
								@endcan
								<th>No</th>
								<th>Type</th>
								<th>Vendor name</th>
								<th>Agg vendor</th>
								<th>Product code</th>
								<th>Discount(%)</th>
								<th>Valid from date</th>
								<th>Valid to date</th>
								<th>Image</th>
								<th width="227px">Action</th>
							</tr>
							@if($discounts->total() > 0)
								@foreach ($discounts as $key => $discount)
								<tr>
									@can('discount-delete')
										<td><input type="checkbox" class="sub_chk" data-id="{{$discount->id}}"></td>
									@endcan
									<td>{{ ($discounts->currentPage()-1) * $discounts->perPage() + $loop->index + 1 }}</td>
									<td>{{ $discount->vendor_type }}</td>
									<td>{{ $discount->vendor_name }}</td>
									<td>{{ $discount->aggregator_vendor_name }}</td>
									<td>{{ $discount->product_code}}</td>
									<td>{{ $discount->discount }}%</td>
									<td> {{ \Carbon\Carbon::parse($discount->valid_from_date)->format('d-m-Y h:i A')}}</td>
									<td>{{ \Carbon\Carbon::parse($discount->valid_to_date)->format('d-m-Y h:i A')}}</td>
									<td><img src="{{ asset($discount->image_url) }}" width="80px" height="50px"></td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('discounts.show',$discount->id) }}">Show</a>
										@can('discount-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('discounts.edit',$discount->id) }}">Edit</a>
										@endcan
										@can('discount-delete')
											<form method="POST" action="{{ route('discounts.destroy',$discount->id) }}" style="display:inline">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger btn-sm">
													{{ __('Delete') }}
												</button>
											</form>
										@endcan
									</td>
								</tr>
								@endforeach
							@else
								@can('discount-delete')
									<tr><td colspan="11">No records found.</td></tr>
								@else
									<tr><td colspan="10">No records found.</td></tr>
								@endcan
								
							@endif
						</table>
						{{ $discounts->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/discount.js') }}" defer></script>
@endsection