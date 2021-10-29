@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Performance master') }}
					</div>
					<div class="float-right">
						@can('performances-create')
							<a class="btn btn-success btn-sm" href="{{ route('performances.create') }}"> Create New Performances</a>
						@endcan
						@can('performances-import-export')
							<a class="btn btn-dark btn-sm" href="{{ route('performances-import-export') }}"> Performances import/export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('performances.index') }}"> Reset Search</a>
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
							@can('performances-delete')
								<form method="POST" action="{{ route('deleteperformancesall') }}" style="display:inline">
									@csrf
									@method('DELETE')
									<input type="hidden" id="selectedval" name="selectedval">
									<button type="submit" class="btn btn-primary btn-sm delete_all">
										{{ __('Delete All Selected') }}
									</button>
								</form>
							@endcan 
							Showing {{($performances->currentPage()-1)* $performances->perPage()+($performances->total() ? 1:0)}} to {{($performances->currentPage()-1)*$performances->perPage()+count($performances)}}  of  {{$performances->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('performances.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search performance" value="{{ $search }}"> <span class="input-group-btn">
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
								@can('performances-delete')
									<th><input type="checkbox" id="master"></th>
								@endcan
								<th>No</th>
								<th>Product code</th>
								<th>Category</th>
								<th>Sale Through(%)</th>
								<th width="227px">Action</th>
							</tr>
							@if($performances->total() > 0)
								@foreach ($performances as $key => $val)
								<tr>
									@can('performances-delete')
										<td><input type="checkbox" class="sub_chk" data-id="{{$val->id}}"></td>
									@endcan
									<td>{{ ($performances->currentPage()-1) * $performances->perPage() + $loop->index + 1 }}</td>
									<td>{{ $val->product_code}}</td>
									<td>{{ $val->category }}</td>
									<td>{{ $val->sale_through }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('performances.show',$val->id) }}">Show</a>
										@can('performances-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('performances.edit',$val->id) }}">Edit</a>
										@endcan
										@can('performances-delete')
											<form method="POST" action="{{ route('performances.destroy',$val->id) }}" style="display:inline">
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
								@can('performances-delete')
									<tr><td colspan="6">No records found.</td></tr>
								@else
									<tr><td colspan="5">No records found.</td></tr>
								@endcan
								
							@endif
						</table>
						{{ $performances->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/performance.js') }}" defer></script>
@endsection