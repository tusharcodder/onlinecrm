@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-2">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Discount Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('searchdiscountreport') }}" id="searchform" name="searchform" method="GET">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Type') }}</label>
									<select class="form-control" id="type" name="type">
										<option value="">-- Select --</option>
										@foreach ($vtype as $key => $val)
											<option value="{{ $val }}" {{ $val == $type ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor" class="col-form-label text-md-right">{{ __('Vendor') }}</label>
									<input type="hidden" value="{{$vendor}}" id="selvendor" name="selvendor">
									<select class="form-control" id="vendor" name="vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group aggregatorcontainer" style="display:none;">
									<label for="aggregator_vendor" class="col-form-label text-md-right">{{ __('Aggregator vendor') }}</label>
									<input type="hidden" value="{{ $aggregator_vendor }}" name="selaggvendor" id="selaggvendor">
									<select class="form-control" id="aggregator_vendor" name="aggregator_vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
						</div>
						
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
									<label for="type" class="col-form-label text-md-right">{{ __('Product code') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ $product_code }}"  autocomplete="product_code" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date" class="col-form-label text-md-right">{{ __('From date') }}</label>
									<input id="from_date" type="datetime-local" class="form-control" name="from_date" value="{{ $from_date }}"  autocomplete="from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="to_date" class="col-form-label text-md-right">{{ __('To date') }}</label>
									<input id="to_date" type="datetime-local" class="form-control" name="to_date" value="{{ $to_date }}"  autocomplete="to_date">
								</div>
							</div>
						</div>
						
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="searchreport">
                                    {{ __('Search') }}
                                </button>
								@if(!empty($request->input()))
									<a href="{{ route('discountreport') }}" class="btn btn-info">{{ __('Reset') }}
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
					@can('download-discount-report')
						<form action="{{ route('downloaddiscountreport') }}" method="POST">
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
							Showing {{($discountreports->currentPage()-1)* $discountreports->perPage()+($discountreports->total() ? 1:0)}} to {{($discountreports->currentPage()-1)*$discountreports->perPage()+count($discountreports)}}  of  {{$discountreports->total()}}  Results
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th>No</th>
								<th>Type</th>
								<th>Vendor name</th>
								<th>Agg vendor</th>
								<th>Brand</th>
								<th>Category</th>
								<th>Gender</th>
								<th>Lotno</th>
								<th>Product code</th>
								<th>Closing stock</th>
								<th>Cost</th>
								<th>Online MRP</th>
								<th>Offline MRP</th>
								<th>Discount(%)</th>
								<th>Sale Through(%)</th>
								<th>Valid from date</th>
								<th>Valid to date</th>
								<th>Image</th>
							</tr>
							@if($discountreports->total() > 0)
								@foreach ($discountreports as $key => $discount)
								
								@php
									$discount->quantity = empty($discount->quantity) ? 0 : $discount->quantity;
									$discount->sale_qty = empty($discount->sale_qty) ? 0 : $discount->sale_qty;
									
									$salethrough = 0;
									if($discount->quantity > 0){
										$salethrough = ($discount->sale_qty * 100)/$discount->quantity;
										$salethrough = number_format((float)$salethrough, 2, '.', '');
									}
								@endphp
								<tr>
									<td>{{ ($discountreports->currentPage()-1) * $discountreports->perPage() + $loop->index + 1 }}</td>
									<td>{{ $discount->vendor_type }}</td>
									<td>{{ $discount->vendor_name }}</td>
									<td>{{ $discount->aggregator_vendor_name }}</td>
									<td>{{ $discount->brand}}</td>
									<td>{{ $discount->category}}</td>
									<td>{{ $discount->gender}}</td>
									<td>{{ $discount->lotno}}</td>
									<td>{{ $discount->product_code}}</td>
									<td>{{ $discount->quantity - $discount->sale_qty}}</td>
									<td>{{ $discount->cost}}</td>
									<td>{{ $discount->online_mrp}}</td>
									<td>{{ $discount->offline_mrp}}</td>
									<td>{{ $discount->discount }}%</td>
									<td>{{ $salethrough }}%</td>
									<td> {{ \Carbon\Carbon::parse($discount->valid_from_date)->format('d-m-Y h:i A')}}</td>
									<td>{{ \Carbon\Carbon::parse($discount->valid_to_date)->format('d-m-Y h:i A')}}</td>
									<td>
										@if(!empty($discount->img_url))
										<img src="{{ asset($discount->image_url) }}" width="80px" height="50px">
										@endif
									</td>
								</tr>
								@endforeach
							@else
								<tr><td colspan="18">No records found.</td></tr>
							@endif
						</table>
						{{ $discountreports->render() }}
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/discount.js') }}" defer></script>
@endsection