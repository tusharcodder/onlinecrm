@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Manufacturer Management') }}
					</div>
					<div class="float-right">
						@can('manufacturer-create')
							<a class="btn btn-success btn-sm" href="{{ route('buyers.create') }}"> Create New Manufacturer</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('buyers.index') }}"> Reset Search</a>
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
							Showing {{($buyers->currentPage()-1)* $buyers->perPage()+($buyers->total() ? 1:0)}} to {{($buyers->currentPage()-1)*$buyers->perPage()+count($buyers)}}  of  {{$buyers->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('buyers.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search buyers" value="{{ $search }}"> <span class="input-group-btn">
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
								<th>Name</th>
								<th>Country</th>
								<th>Address</th>
								<th width="280px">Action</th>
							</tr>
							@if($buyers->total() > 0)
								@foreach ($buyers as $key => $buyer)
								<tr>
									<td>{{ ($buyers->currentPage()-1) * $buyers->perPage() + $loop->index + 1 }}</td>
									<td>{{ $buyer->name }}</td>
									<td>{{ $buyer->country }}</td>
									<td>{{ $buyer->address }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('buyers.show',$buyer->id) }}">Show</a>
										@can('manufacturer-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('buyers.edit',$buyer->id) }}">Edit</a>
										@endcan
										@can('manufacturer-delete')
											<form method="POST" action="{{ route('buyers.destroy',$buyer->id) }}" style="display:inline">
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
								<tr><td colspan="5">No records found.</td></tr>
							@endif
						</table>
						{{ $buyers->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection