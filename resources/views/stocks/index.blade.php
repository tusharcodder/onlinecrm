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
						@can('stock-transfer')
							<a class="btn btn-secondary btn-sm" href="{{ route('stock-transfer') }}">Stock Transfer</a>
						@endcan
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
					@can('download-tjw-stock')
						<form action="{{route('export-stock')}}" method="POST">
						@csrf
							<div class="form-group row">
								<div class="col-md-2">
								<input type="hidden" name="hiddensearch" id="hiddensearch" value="{{$search}}" />
									<label for="exporttype" class="">{{ __('File Type*') }}</label> 
									<select class="form-control" id="exporttype" name="exporttype">
										<option value="csv">CSV</option>
										<option value="xls">XLS</option>
										<option value="xlsx">XLSX</option>
									</select>
								</div>
								<div class="col-md-1">
									<input type="hidden" value="" id="formval" name="formval">
									<button type="submit" class="btn btn-primary" style="margin-top: 30px !important;" id="downloadreport">
										{{ __('Download') }}
									</button>
								</div>
							</div>
						</form>
					@endcan
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
								<th  style="width:5%;">#</th>  
								<th style="width:15%;">Warehouse</th>
								<th  style="width:15%;"> ISBN13</th>								
								<th>Book Title</th>								
                                <th  style="width:10%;">Stock</th>                            
							</tr>
							@if($stocks->total() > 0)                          
								@foreach ($stocks as $key => $stock)
								<tr>
									<td>{{ ($stocks->currentPage()-1) * $stocks->perPage() + $loop->index + 1 }}</td>
                                   	<td>{{ $stock->name}}</td>									
									<td>{{ $stock->isbn13}}</td>   
                                    <td>{{ $stock->book_title}}</td>
                                    <td>{{ $stock->stock}}</td>
								</tr>                               
								@endforeach
							@else
								<tr><td colspan="5">No records found.</td></tr>
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