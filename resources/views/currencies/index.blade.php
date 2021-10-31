@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Currencies Management') }}
					</div>
					<div class="float-right">
						@can('currencies-create')
							<a class="btn btn-success btn-sm" href="{{ route('currencies.create') }}"> Create New Currencies</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('currencies.index') }}"> Reset Search</a>
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
							Showing {{($currenciess->currentPage()-1)* $currenciess->perPage()+($currenciess->total() ? 1:0)}} to {{($currenciess->currentPage()-1)*$currenciess->perPage()+count($currenciess)}}  of  {{$currenciess->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('currencies.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search currencies" value="{{ $search }}"> <span class="input-group-btn">
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
								<th>Symbol</th>
								<th width="227px">Action</th>
							</tr>
							@if($currenciess->total() > 0)
								@foreach ($currenciess as $key => $currencies)
								<tr>
									<!--<td>{{ ($currenciess->currentPage()-1) * $currenciess->perPage() + $loop->index + 1 }}</td>-->
									<td>{{ $currencies->id }}</td>
									<td>{{ $currencies->name }}</td>
									<td>{{ $currencies->symbol }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('currencies.show',$currencies->id) }}">Show</a>
										@can('currencies-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('currencies.edit',$currencies->id) }}">Edit</a>
										@endcan
										@can('currencies-delete')
											<form method="POST" action="{{ route('currencies.destroy',$currencies->id) }}" style="display:inline">
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
								<tr><td colspan="4">No records found.</td></tr>
							@endif
						</table>
						{{ $currenciess->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection