@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Users Management') }}
					</div>
					<div class="float-right">
						@if (Route::has('register'))
							@can('user-create')
								<a class="btn btn-success btn-sm" href="{{ route('register') }}"> Create New User</a>
							@endcan
						@endif
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> Reset Search</a>
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
							Showing {{($data->currentPage()-1)* $data->perPage()+($data->total() ? 1:0)}} to {{($data->currentPage()-1)*$data->perPage()+count($data)}}  of  {{$data->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('users.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search users" value="{{ $search }}"> <span class="input-group-btn">
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
								<th>Email</th>
								<th>Roles</th>
								<th width="280px">Action</th>
							</tr>
							@if($data->total() > 0)
								@foreach ($data as $key => $user)
									<tr>
										<td>{{ ($data->currentPage()-1) * $data->perPage() + $loop->index + 1 }}</td>
										<td>{{ $user->name }}</td>
										<td>{{ $user->email }}</td>
										<td>
										  @if(!empty($user->getRoleNames()))
											@foreach($user->getRoleNames() as $v)
											   <label class="badge badge-success">{{ $v }}</label>
											@endforeach
										  @endif
										</td>
										<td>
										   <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}">Show</a>
											@can('user-edit')
												<a class="btn btn-primary  btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
											@endcan
											@can('user-delete')
												<form method="POST" action="{{ route('users.destroy',$user->id) }}" style="display:inline">
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
						{{ $data->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection