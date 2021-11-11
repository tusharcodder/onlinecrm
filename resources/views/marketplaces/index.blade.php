@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Market Place Management Tushar Gupta') }}
					</div>
					<div class="float-right">
						@can('market-place-create')
							<a class="btn btn-success btn-sm" href="{{ route('marketplaces.create') }}"> Create New Market Place</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('marketplaces.index') }}"> Reset Search</a>
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
							Showing {{($marketplaces->currentPage()-1)* $marketplaces->perPage()+($marketplaces->total() ? 1:0)}} to {{($marketplaces->currentPage()-1)*$marketplaces->perPage()+count($marketplaces)}}  of  {{$marketplaces->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('marketplaces.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search market places" value="{{ $search }}"> <span class="input-group-btn">
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
								<th>#ID</th>
								<th>Name</th>
								<th>Phone Number</th>
								<th>Email</th>
								<th>Address</th>
								<th width="227px">Action</th>
							</tr>
							@if($marketplaces->total() > 0)
								@foreach ($marketplaces as $key => $marketplace)
								<tr>
									<!--<<td>{{ ($marketplaces->currentPage()-1) * $marketplaces->perPage() + $loop->index + 1 }}</td>-->
									<td>{{ $marketplace->id }}</td>
									<td>{{ $marketplace->name }}</td>
									<td>{{ $marketplace->number }}</td>
									<td>{{ $marketplace->email}}</td>
									<td>{{ $marketplace->address }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('marketplaces.show',$marketplace->id) }}">Show</a>
										@can('market-place-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('marketplaces.edit',$marketplace->id) }}">Edit</a>
										@endcan
										@can('market-place-delete')
											<form method="POST" action="{{ route('marketplaces.destroy',$marketplace->id) }}" style="display:inline">
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
								<tr><td colspan="6">No records found.</td></tr>
							@endif
						</table>
						{{ $marketplaces->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection