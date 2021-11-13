@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Vendor Stock Management') }}
					</div>
					<div class="float-right">
						@can('vendor-stock-create')
							<a class="btn btn-success btn-sm" href="{{ route('vendorstocks.create') }}"> Add New Vendor Stock</a>
						@endcan
						@can('vendor-stock-import-export')
							<a class="btn btn-secondary btn-sm" href="{{ route('vendor-stock-import-export') }}"> Stock import/export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('vendorstocks.index') }}"> Reset Search</a>
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
							<!--@can('vendor-stock-delete')
								<form method="POST" action="{{ route('deletevendorstockall') }}" style="display:inline">
									@csrf
									@method('DELETE')
									<input type="hidden" id="selectedval" name="selectedval">
									<button type="submit" class="btn btn-primary btn-sm delete_all">
										{{ __('Delete All Selected') }}
									</button>
								</form>
							@endcan -->
							Showing {{($stocks->currentPage()-1)* $stocks->perPage()+($stocks->total() ? 1:0)}} to {{($stocks->currentPage()-1)*$stocks->perPage()+count($stocks)}}  of  {{$stocks->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('vendorstocks.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search vendor stocks" value="{{ $search }}"> <span class="input-group-btn">
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
								<!--@can('vendor-stock-delete')
									<th><input type="checkbox" id="master"></th>
								@endcan-->
								<th>No</th>
								<th>Stock date</th>
								<th>Vendor name</th>
								<th>ISBN no</th>
								<th>Name</th>
								<th>Author</th>
								<th>Publisher</th>
								<th>Binding type</th>
								<th>Currency</th>
								<th>Price</th>
								<th>Discount</th>
								<th>Qty</th>
								<th width="227px">Action</th>
							</tr>
							@if($stocks->total() > 0)
								@foreach ($stocks as $key => $stock)
								<tr>
									<!--@can('vendor-stock-delete')
										<td><input type="checkbox" class="sub_chk" data-id="{{$stock->id}}"></td>
									@endcan-->
									<td>{{ ($stocks->currentPage()-1) * $stocks->perPage() + $loop->index + 1 }}</td>
									<td> {{ \Carbon\Carbon::parse($stock->stock_date)->format('d-m-Y')}}</td>
									<td>{{ $stock->vendor_name }}</td>
									<td>{{ $stock->isbnno }}</td>
									<td>{{ $stock->name }}</td>
									<td>{{ $stock->author }}</td>
									<td>{{ $stock->publisher }}</td>
									<td>{{ $stock->binding_type }}</td>
									<td>{{ $stock->currency }}</td>
									<td>{{ $stock->price }}</td>
									<td>{{ $stock->discount }}</td>
									<td>{{ $stock->quantity }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('vendorstocks.show',$stock->id) }}">Show</a>
										@can('vendor-stock-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('vendorstocks.edit',$stock->id) }}">Edit</a>
										@endcan
										@can('vendor-stock-delete')
											<form method="POST" action="{{ route('vendorstocks.destroy',$stock->id) }}" style="display:inline">
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
								<tr>
									<td colspan="13">No records found.</td>
									<!--@can('vendor-stock-delete')
										<td colspan="14">No records found.</td>
									@else
										<td colspan="13">No records found.</td>
									@endcan-->
								</tr>
							@endif
						</table>
						{{ $stocks->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/vendorstock.js') }}" defer></script>
@endsection