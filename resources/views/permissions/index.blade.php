@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Permission Management') }}
					</div>
					<div class="float-right">
						<a class="btn btn-success btn-sm" href="{{ route('permissions.create') }}"> Create New Permission</a>
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('permissions.index') }}"> Reset Search</a>
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
							Showing {{($permissions->currentPage()-1)* $permissions->perPage()+($permissions->total() ? 1:0)}} to {{($permissions->currentPage()-1)*$permissions->perPage()+count($permissions)}}  of  {{$permissions->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('permissions.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search permissions" value="{{ $search }}"> <span class="input-group-btn">
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
							@if($permissions->total() > 0)
								@foreach ($permissions as $key => $per)
								<tr>
									<td>{{ ($permissions->currentPage()-1) * $permissions->perPage() + $loop->index + 1 }}</td>
									<td>{{ $per->name }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('permissions.show',$per->id) }}">Show</a>
										<a class="btn btn-primary btn-sm" href="{{ route('permissions.edit',$per->id) }}">Edit</a>
										<form method="POST" action="{{ route('permissions.destroy',$per->id) }}" style="display:inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger btn-sm">
												{{ __('Delete') }}
											</button>
										</form>
									</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="3">No records found.</td></tr>
							@endif
						</table>
						{{ $permissions->render() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection