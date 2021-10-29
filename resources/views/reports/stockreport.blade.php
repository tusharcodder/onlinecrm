@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-2">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Stock Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('searchstockreport') }}" id="searchform" name="searchform" method="GET">
						@csrf
						<div class="row">							
							<div class="col-md-3">
								<div class="form-group">
									<label for="brand" class="col-form-label text-md-right">{{ __('Brand') }}</label>
									<input id="brand" type="text" class="form-control" name="brand" value="{{ $brand }}"  autocomplete="brand" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="category" class="col-form-label text-md-right">{{ __('Category') }}</label>
									<input id="category" type="text" class="form-control" name="category" value="{{ $category }}"  autocomplete="category" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="gender" class="col-form-label text-md-right">{{ __('Gender') }}</label>
									<input id="gender" type="text" class="form-control" name="gender" value="{{ $gender }}"  autocomplete="gender" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="colour" class="col-form-label text-md-right">{{ __('Colour') }}</label>
									<input id="colour" type="text" class="form-control" name="colour" value="{{ $colour }}"  autocomplete="colour" >
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="lotno" class="col-form-label text-md-right">{{ __('Lot no') }}</label>
									<input id="lotno" type="text" class="form-control" name="lotno" value="{{ $lotno }}"  autocomplete="lotno" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_code" class="col-form-label text-md-right">{{ __('Product code') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ $product_code }}"  autocomplete="product_code" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="sku_code" class="col-form-label text-md-right">{{ __('SKU code') }}</label>
									<input id="sku_code" type="text" class="form-control" name="sku_code" value="{{ $sku_code }}"  autocomplete="sku_code" >
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date" class="col-form-label text-md-right">{{ __('From date') }}</label>
									<input id="from_date" type="date" class="form-control" name="from_date" value="{{ $from_date }}"  autocomplete="from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="to_date" class="col-form-label text-md-right">{{ __('To date') }}</label>
									<input id="to_date" type="date" class="form-control" name="to_date" value="{{ $to_date }}"  autocomplete="to_date">
								</div>
							</div>
						</div>
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="searchreport">
                                    {{ __('Search') }}
                                </button>
								@if(!empty($request->input()))
									<a href="{{ route('stockreport') }}" class="btn btn-info">{{ __('Reset') }}
									</a>
								@endif
                            </div>
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>
	
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('List') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					@can('download-stock-report')
						<form action="{{ route('downloadstockreport') }}" method="POST">
						@csrf
							<div class="form-group row">
								<div class="col-md-2">
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
							Showing {{($stockreports->currentPage()-1)* $stockreports->perPage()+($stockreports->total() ? 1:0)}} to {{($stockreports->currentPage()-1)*$stockreports->perPage()+count($stockreports)}}  of  {{$stockreports->total()}}  Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>Brand</th>
								<th>Category</th>
								<th>Gender</th>
								<th>Colour</th>
								<th>Size</th>
								<th>Lotno</th>
								<th>Sku code</th>
								<th>Product code</th>
								<th>Hsn code</th>
								<th>Description</th>
								<th>Opening stock date</th>
								<th>Opening stock</th>
								<th>Closing stock</th>
								<th>Sale Through(%)</th>
								<th>Online MRP</th>
								<th>Offline MRP</th>
								<th>Cost</th>
								<th>Total cost</th>
								<th>Image</th>
							</tr>
							@if($stockreports->total() > 0)
								@foreach ($stockreports as $key => $stock)
								
								@php
									$stock->quantity = empty($stock->quantity) ? 0 : $stock->quantity;
									$stock->closing_qty = empty($stock->closing_qty) ? 0 : $stock->closing_qty;
									$stock->sale_qty = empty($stock->sale_qty) ? 0 : $stock->sale_qty;
									$stock->net_sale_qty = empty($stock->net_sale_qty) ? 0 : $stock->net_sale_qty;
									
									$salethrough = 0;
									if( $stock->closing_qty > 0 ){
										$salethrough = ($stock->sale_qty * 100) / $stock->closing_qty;
										$salethrough = number_format((float)$salethrough, 2, '.', '');
									}
					
								@endphp
								
								<tr>
									<td>{{ ($stockreports->currentPage()-1) * $stockreports->perPage() + $loop->index + 1 }}</td>
									<td>{{ $stock->brand}}</td>
									<td>{{ $stock->category}}</td>
									<td>{{ $stock->gender}}</td>
									<td>{{ $stock->colour}}</td>
									<td>{{ $stock->size}}</td>
									<td>{{ $stock->lotno}}</td>
									<td>{{ $stock->sku_code}}</td>
									<td>{{ $stock->product_code}}</td>
									<td>{{ $stock->hsn_code}}</td>
									<td>{{ $stock->description}}</td>
									<td> {{ \Carbon\Carbon::parse($stock->stock_date)->format('d-m-Y')}}</td>
									<td>{{ $stock->quantity }}</td>
									<td>{{ $stock->quantity - $stock->net_sale_qty }}</td>
									<td>{{ $salethrough }}%</td>
									<td>{{ $stock->online_mrp}}</td>
									<td>{{ $stock->offline_mrp}}</td>
									<td>{{ $stock->cost }}</td>
									<td>{{ $stock->cost * $stock->quantity}}</td>
									<td>
										@if(!empty($stock->img_url))
										<img src="{{ asset($stock->img_url) }}" width="80px" height="50px">
										@endif
									</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="19">No records found.</td></tr>
							@endif
						</table>
						{{ $stockreports->links() }}
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/stock.js') }}" defer></script>
@endsection