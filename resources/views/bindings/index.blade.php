@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Binding Management') }}
					</div>
					<div class="float-right">
						@can('binding-create')
							<a class="btn btn-success btn-sm" href="{{ route('bindings.create') }}"> Create New Binding</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('bindings.index') }}"> Reset Search</a>
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
							Showing {{($bindings->currentPage()-1)* $bindings->perPage()+($bindings->total() ? 1:0)}} to {{($bindings->currentPage()-1)*$bindings->perPage()+count($bindings)}}  of  {{$bindings->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('bindings.index') }}" role="search">
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
								<th>No</th>
								<th>Name</th>
								<th width="227px">Action</th>
							</tr>
							@if($bindings->total() > 0)
								@foreach ($bindings as $key => $binding)
								<tr>
									<td>{{ ($bindings->currentPage()-1) * $bindings->perPage() + $loop->index + 1 }}</td>
									<td>{{ $binding->name }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('bindings.show',$binding->id) }}">Show</a>
										@can('binding-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('bindings.edit',$binding->id) }}">Edit</a>
										@endcan
										@can('binding-delete')
											<form method="POST" action="{{ route('bindings.destroy',$binding->id) }}" style="display:inline">
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
								<tr><td colspan="1">No records found.</td></tr>
							@endif
						</table>
						{{ $bindings->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection