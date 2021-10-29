@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Role Management') }}
					</div>
					<div class="float-right">
						@can('role-create')
							<a class="btn btn-success btn-sm" href="{{ route('roles.create') }}"> Create New Role</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('roles.index') }}"> Reset Search</a>
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
							Showing {{($roles->currentPage()-1)* $roles->perPage()+($roles->total() ? 1:0)}} to {{($roles->currentPage()-1)*$roles->perPage()+count($roles)}}  of  {{$roles->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('roles.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search roles" value="{{ $search }}"> <span class="input-group-btn">
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
								<th width="280px">Action</th>
							</tr>
							@if($roles->total() > 0)
								@foreach ($roles as $key => $role)
								<tr>
									<td>{{ ($roles->currentPage()-1) * $roles->perPage() + $loop->index + 1 }}</td>
									<td>{{ $role->name }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}">Show</a>
										@can('role-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}">Edit</a>
										@endcan
										@can('role-delete')
											<form method="POST" action="{{ route('roles.destroy',$role->id) }}" style="display:inline">
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
								<tr><td colspan="3">No records found.</td></tr>
							@endif
						</table>
						{{ $roles->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection