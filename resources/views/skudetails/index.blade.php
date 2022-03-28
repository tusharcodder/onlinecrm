@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Sku Code Management') }}
					</div>
					<div class="float-right">
						@can('sku-create')
							<a class="btn btn-success btn-sm" href="{{ route('skudetails.create') }}"> Create New Sku Code</a>
						@endcan
						@can('sku-import-export')
							<a class="btn btn-secondary btn-sm" href="{{ route('skucode-detail-import-export') }}"> Sku Import/Export</a>
						@endcan
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('skudetails.index') }}"> Reset Search</a>
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
							Showing {{($skudetails->currentPage()-1)* $skudetails->perPage()+($skudetails->total() ? 1:0)}} to {{($skudetails->currentPage()-1)*$skudetails->perPage()+count($skudetails)}}  of  {{$skudetails->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('skudetails.index') }}" role="search">
								<div class="input-group">
									<input type="text" class="form-control" name="search"
										placeholder="Search" value="{{ $search }}"> <span class="input-group-btn">
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
								<th>#</th>
								<th>Market Place</th>
								<!--<th>Warehouse</th>-->
								<th>ISBN13</th>
								<th>ISBN10</th>
                                <th>Sku Code</th>
                                <th>MRP</th>
                                <th>Disc</th>
                                <th>Weight(kg)</th>                              
								<th>Ounces wt</th>
								<th>Type</th>
								<th width="227px">Action</th>
							</tr>
							@if($skudetails->total() > 0)                          
								@foreach ($skudetails as $key => $skudetail)
								<tr>
									<!--<<td>{{ ($skudetails->currentPage()-1) * $skudetails->perPage() + $loop->index + 1 }}</td>-->
									<td>{{ ($skudetails->currentPage()-1) * $skudetails->perPage() + $loop->index + 1 }}</td>
									<td>{{ $skudetail->mplace }}</td>
									<!--<td>{{ $skudetail->warehouse }}</td>-->
									<td>{{ $skudetail->isbn13}}</td>
                                    <td>{{ $skudetail->isbn10}}</td>
									<td>{{ $skudetail->sku_code}}</td>
                                    <td>{{ $skudetail->mrp}}</td>
                                    <td>{{ $skudetail->disc}}</td>
                                    <td>{{ $skudetail->wght}}</td>									
									<td>{{ $skudetail->oz_wt }}</td>
									<td>{{ $skudetail->type }}</td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('skudetails.show',$skudetail->id) }}">Show</a>
										@can('sku-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('skudetails.edit',$skudetail->id) }}">Edit</a>
										@endcan
										@can('sku-delete')
											<form method="POST" action="{{ route('skudetails.destroy',$skudetail->id) }}" style="display:inline">
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
								<tr><td colspan="9">No records found.</td></tr>
							@endif
						</table>
						{{ $skudetails->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection