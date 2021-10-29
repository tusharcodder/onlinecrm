@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('GST Slabs') }}
					</div>
					<div class="float-right">
						@can('gstslab-create')
							<a class="btn btn-success btn-sm" href="{{ route('gstslab.create') }}"> Create New Gst Slab</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('gstslab.index') }}"> Reset Search</a>
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
							Showing {{($gstslb->currentPage()-1)* $gstslb->perPage()+($gstslb->total() ? 1:0)}} to {{($gstslb->currentPage()-1)*$gstslb->perPage()+count($gstslb)}}  of  {{$gstslb->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('gstslab.index') }}" role="search">
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
								<th>Amount From</th>
								<th>Amount To</th>
								<th>Gst(%)</th>
								<th width="280px">Action</th>
							</tr>
							@if($gstslb->total() > 0)
								@foreach ($gstslb as $key => $gstslbval)
								<tr>
									<td>{{ ($gstslb->currentPage()-1) * $gstslb->perPage() + $loop->index + 1 }}</td>
									<td>{{ $gstslbval->amount_from }}</td>
									<td>{{ $gstslbval->amount_to }}</td>
									<td>{{ $gstslbval->gst_per }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('gstslab.show',$gstslbval->id) }}">Show</a>
										@can('gstslab-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('gstslab.edit',$gstslbval->id) }}">Edit</a>
										@endcan
										@can('gstslab-delete')
											<form method="POST" action="{{ route('gstslab.destroy',$gstslbval->id) }}" style="display:inline">
												@csrf
												@method('DELETE')
												<button type="submit" onclick="myFunction();" class="btn btn-danger btn-sm">
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
						{{ $gstslb->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
<script>
  function myFunction() {
      if(!confirm("Are You Sure to delete this"))
      event.preventDefault();
  }
 </script>
@endsection