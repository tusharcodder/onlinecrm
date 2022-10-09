@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Zone Price Management') }}
					</div>
					<div class="float-right">
						@can('currencies-create')
							<a class="btn btn-success btn-sm" href="{{ route('uspszoneprice.create') }}"> Create New Zone Price</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('uspszoneprice.index') }}"> Reset Search</a>
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
							Showing {{($zoneprice->currentPage()-1)* $zoneprice->perPage()+($zoneprice->total() ? 1:0)}} to {{($zoneprice->currentPage()-1)*$zoneprice->perPage()+count($zoneprice)}}  of  {{$zoneprice->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('uspszoneprice.index') }}" role="search">
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
								<th>Zone </th>
								<th>Weight(lbs)</th>
                                <th>Price</th>
								<th width="227px">Action</th>
							</tr>
							@if($zoneprice->total() > 0)
								@foreach ($zoneprice as $key => $zonelist)
								<tr>									
									<td>{{ $zonelist->id }}</td>
									<td>{{ $zonelist->zone_name }}</td>
									<td>{{ $zonelist->lbs_wgt_from }} - {{$zonelist->lbs_wgt_to}}</td>
                                    <td>{{ $zonelist->zone_price }}</td>
									<td>
										
										@can('currencies-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('uspszoneprice.edit',$zonelist->id) }}">Edit</a>
										@endcan
										@can('currencies-delete')
											<form method="POST" action="{{ route('uspszoneprice.destroy',$zonelist->id) }}" style="display:inline">
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
						{{ $zoneprice->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection