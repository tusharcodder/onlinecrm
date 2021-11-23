@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Manage TJW Stock') }}
					</div>
					<div class="float-right">
						@can('purchase-import-export')
							<a class="btn btn-secondary btn-sm" href="{{ route('purchase-order-import-export') }}">Purchase Order import/export</a>
						@endcan
						@can('purchase-order-list')
							<a class="btn btn-success btn-sm" href="{{ route('purchaseorders.index') }}">View Purchase Order Details</a>
						@endcan						
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('stocklist') }}"> Reset Search</a>
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
							Showing {{($stocks->currentPage()-1)* $stocks->perPage()+($stocks->total() ? 1:0)}} to {{($stocks->currentPage()-1)*$stocks->perPage()+count($stocks)}}  of  {{$stocks->total()}}  Results
						</div>
						<div class="col-sm-4">
							<form method="GET" action="{{ route('stocklist') }}" role="search">
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
								<th>ISBN13</th>								
								<th>Book Title</th>								
                                <th>Stock</th>                            
							</tr>
							@if($stocks->total() > 0)                          
								@foreach ($stocks as $key => $stock)
								<tr>
									<td>{{ ($stocks->currentPage()-1) * $stocks->perPage() + $loop->index + 1 }}</td>
                                   	<td>{{ $stock->isbn13}}</td>									
                                    <td>{{ $stock->book_title}}</td>
                                    <td>{{ $stock->stock}}</td>
								</tr>                               
								@endforeach
							@else
								<tr><td colspan="4">No records found.</td></tr>
							@endif
						</table>
						{{ $stocks->links() }}
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>
@endsection