@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Vendor Management') }}
					</div>
					<div class="float-right">
						@can('vendor-create')
							<a class="btn btn-success btn-sm" href="{{ route('vendorss.create') }}"> Create New Vendor</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('vendorss.index') }}"> Reset Search</a>
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
							Showing {{($vendors->currentPage()-1)* $vendors->perPage()+($vendors->total() ? 1:0)}} to {{($vendors->currentPage()-1)*$vendors->perPage()+count($vendors)}}  of  {{$vendors->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('vendorss.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search vendors" value="{{ $search }}"> <span class="input-group-btn">
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
								<th>No</th>
								<th>Type</th>
								<th>Vendor name</th>
								<th>Conatct person name</th>
								<th>Conatct person email ID</th>
								<th>Conatct person number</th>
								<th>Commision(%)</th>
								<th width="227px">Action</th>
							</tr>
							@if($vendors->total() > 0)
								@foreach ($vendors as $key => $vendor)
								<tr>
									<td>{{ ($vendors->currentPage()-1) * $vendors->perPage() + $loop->index + 1 }}</td>
									<td>{{ $vendor->type }}</td>
									<td>{{ $vendor->vendor_name }}</td>
									<td>{{ $vendor->contact_person_name}}</td>
									<td>{{ $vendor->contact_person_email }}</td>
									<td>{{ $vendor->contact_person_number }}</td>
									<td>{{ $vendor->commission }}%</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('vendorss.show',$vendor->id) }}">Show</a>
										@can('vendor-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('vendorss.edit',$vendor->id) }}">Edit</a>
										@endcan
										@can('vendor-delete')
											<form method="POST" action="{{ route('vendorss.destroy',$vendor->id) }}" style="display:inline">
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
								<tr><td colspan="8">No records found.</td></tr>
							@endif
						</table>
						{{ $vendors->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection