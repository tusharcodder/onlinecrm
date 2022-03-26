@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Warehouse Management') }}
					</div>
					<div class="float-right">
						@can('warehouse-create')
							<a class="btn btn-success btn-sm" href="{{ route('warehouse.create') }}"> Create New Warehouse</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('warehouse.index') }}"> Reset Search</a>
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
					@if ($message = Session::get('error'))
						<div class="alert alert-danger">
							<p>{{ $message }}</p>
						</div>
					@endif
					<div class="row mb-1">
						<div class="col-sm-8">	
							Showing {{($warehouses->currentPage()-1)* $warehouses->perPage()+($warehouses->total() ? 1:0)}} to {{($warehouses->currentPage()-1)*$warehouses->perPage()+count($warehouses)}}  of  {{$warehouses->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('warehouse.index') }}" role="search">
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
								<th>Country Code</th>
								<th>Is Shipped</th>								
								<th width="227px">Action</th>
							</tr>
							@if($warehouses->total() > 0)
								@foreach ($warehouses as $key => $warehouse)
								<tr>
									<!--<<td>{{ ($warehouses->currentPage()-1) * $warehouses->perPage() + $loop->index + 1 }}</td>-->
									<td>{{ $warehouse->id }}</td>
									<td>{{ $warehouse->name }}</td>
									<td>{{ $warehouse->country_code }}</td>
									@if($warehouse->is_shipped == 1)
										<td>{{__('True')}}</td>
									@else
										<td>{{__('False')}}</td>
									@endif
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('warehouse.show',$warehouse->id) }}">Show</a>
										@can('warehouse-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('warehouse.edit',$warehouse->id) }}">Edit</a>
										@endcan
										@can('warehouse-delete')
											@if($warehouse->id != 1)
												<form method="POST" action="{{ route('warehouse.destroy',$warehouse->id) }}" style="display:inline">
													@csrf
													@method('DELETE')
													<button type="submit" class="btn btn-danger btn-sm">
														{{ __('Delete') }}
													</button>
												</form>
											@endif
										@endcan
									</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="4">No records found.</td></tr>
							@endif
						</table>
						{{ $warehouses->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection