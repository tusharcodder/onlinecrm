@extends('layouts.app')
@section('content')
<?php
	$skuvalue = "new";
?>

<div class="container-fluid" id="stockdatalist">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Stock Management') }}
					</div>
					<div class="float-right">
						@if(!empty($search))
							<a class="btn btn-primary btn-sm" href="{{ route('bcstocks.index') }}"> Reset Search</a>
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
						<div class="col-sm-4">
							<!-- Scan SKU_CODE here -->
							<input type="text" id="skucode" name="skucode" value=""  autofocus >
							@can('stock-create')
								<!--<a class="btn btn-success btn-sm" href="{{ route('bcstockscreate',$skuvalue) }}" id="updatestock" > Add Stock</a>-->
							@endcan
							<!-- Button to Open the Modal and Add stock -->
							<button type="button" id="updatestock" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Add Stock</button>
						</div>
					</div>
					
					<div class="table-responsive">
						<table class="table table-bordered" id="tblStockData">
							
							<thead> 
								<tr>
									<th>No</th>
									<th>Date</th>
									<th>Brand</th>
									<th>Category</th>
									<th>Gender</th>
									<th>Colour</th>
									<th>Size</th>
									<th>Product code</th>
									<th>Qty</th>
									<th>Image</th>
									<th width="180px">Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
							
							<!--@if($stocks->total() > 0)
								@foreach ($stocks as $key => $stock)
								<tr>
									@can('stock-delete')
										<td><input type="checkbox" class="sub_chk" data-id="{{$stock->id}}"></td>
									@endcan
									<td>{{ ($stocks->currentPage()-1) * $stocks->perPage() + $loop->index + 1 }}</td>
									<td> {{ \Carbon\Carbon::parse($stock->stock_date)->format('d-m-Y')}}</td>
									<td>{{ $stock->brand }}</td>
									<td>{{ $stock->category }}</td>
									<td>{{ $stock->gender }}</td>
									<td>{{ $stock->colour}}</td>
									<td>{{ $stock->size }}</td>
									<td>{{ $stock->product_code }}</td>
									<td>{{ $stock->quantity }}</td>
									<td><img src="{{ asset($stock->image_url) }}" width="80px" height="50px"></td>
									<td>
										<a class="btn btn-info btn-sm" href="{{ route('bcstocks.show',$stock->id) }}">Show</a>
										@can('stock-edit')
											<a class="btn btn-primary btn-sm" href="{{ route('bcstocks.edit',$stock->id) }}">Edit</a>
										@endcan
										@can('stock-delete')
											<form method="POST" action="{{ route('bcstocks.destroy',$stock->id) }}" style="display:inline">
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
								@can('stock-delete')
									<tr><td colspan="12">No records found.</td></tr>
								@else
									<tr><td colspan="11">No records found.</td></tr>
								@endcan
							@endif-->
						</table>
						<!--{{ $stocks->links() }}-->
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Stock Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<div class="card-body">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>Whoops!</strong> There were some problems with your input.<br><br>
						<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
						</ul>
					</div>
				@endif
				
				<form method="POST" action="{{ route('bcstocks.update', $stock->id) }}" id="stockform" enctype = 'multipart/form-data'>
				@csrf
				@method('PATCH')
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="manufacturer_name" class="col-form-label text-md-right">{{ __('Manufacturer name*') }}</label>
								<input id="manufacturer_name" type="text" class="form-control" name="manufacturer_name" value=""  autocomplete="manufacturer_name" required />
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="country" class="col-form-label text-md-right">{{ __('Country') }}</label>
								<input id="country" type="text" class="form-control" name="country" value=""  autocomplete="country"/>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="manufacture_date" class="col-form-label text-md-right">{{ __('Manufacture date*') }}</label>
								<input id="manufacture_date" type="date" class="form-control" name="manufacture_date" value=""  autocomplete="manufacture_date" required />
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="cost" class="col-form-label text-md-right">{{ __('Cost') }}</label>
								<input id="cost" type="number" step="any" class="form-control" name="cost" value="" autocomplete="cost" />
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="stock_date" class="col-form-label text-md-right">{{ __('Stock date*') }}</label>
								<input id="stock_date" type="date" class="form-control" name="stock_date" value=""  autocomplete="stock_date" required>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="brand" class="col-form-label text-md-right">{{ __('Brand*') }}</label>
								<input id="brand" type="text" class="form-control" name="brand" value=""  autocomplete="brand" required/>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="category" class="col-form-label text-md-right">{{ __('Category*') }}</label>
								<input id="category" type="text" class="form-control" name="category" value=""  autocomplete="category" required />
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="gender" class="col-form-label text-md-right">{{ __('Gender') }}</label>
								<input id="gender" type="text" class="form-control" name="gender" value=""  autocomplete="gender"/>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="colour" class="col-form-label text-md-right">{{ __('Colour') }}</label>
								<input id="colour" type="text" class="form-control" name="colour" value=""  autocomplete="colour"/>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="size" class="col-form-label text-md-right">{{ __('Size*') }}</label>
								<input id="size" type="text" class="form-control" name="size" value=""  autocomplete="size" required/>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="lotno" class="col-form-label text-md-right">{{ __('Lotno*') }}</label>
								<input id="lotno" type="text" class="form-control" name="lotno" value=""  autocomplete="lotno" required/>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="sku_code" class="col-form-label text-md-right">{{ __('Sku code*') }}</label>
								<input id="sku_code" type="text" class="form-control" name="sku_code" value=""  autocomplete="sku_code" required />
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="product_code" class="col-form-label text-md-right">{{ __('Product code*') }}</label>
								<input id="product_code" type="text" class="form-control" name="product_code" value=""  autocomplete="product_code" required />
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="hsn_code" class="col-form-label text-md-right">{{ __('Hsn code') }}</label>
								<input id="hsn_code" type="text" class="form-control" name="hsn_code" value=""  autocomplete="hsn_code" />
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="online_mrp" class="col-form-label text-md-right">{{ __('Online mrp*') }}</label>
								<input id="online_mrp" type="number" class="form-control" name="online_mrp" value="" step="any" autocomplete="online_mrp" required />
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group">
								<label for="offline_mrp" class="col-form-label text-md-right">{{ __('Offline mrp*') }}</label>
								<input id="offline_mrp" type="number" class="form-control" name="offline_mrp" value="" step="any" autocomplete="offline_mrp" required/>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="quantity" class="col-form-label text-md-right">{{ __('Quantity*') }}</label>
								<input id="quantity" type="number" class="form-control" name="quantity" value="" step="any" autocomplete="quantity" required />
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
								<label for="product_image" class="col-form-label text-md-right">{{ __('Product image*') }}</label>
								<input id="product_image" type="file" class="form-control" name="product_image" value="" accept=".jpg,.jpeg,.png,.gif,.svg"/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="">
								<img src="" alt="Preview" id="preview" class="img-thumbnail" width="25%">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7">
							<div class="form-group">
								<label for="description" class="col-form-label text-md-right">{{ __('Description') }}</label>
								<textarea class="form-control" rows="4" name="description" id="description"></textarea>
							</div>
						</div>
					</div>
				
					<div class="form-group row mb-0">
						<div class="col-md-12">
							<button type="button" id="save_stock" class="btn btn-primary">
								{{ __('Submit') }}
							</button>
						</div>
					</div>
				</form>
			</div>
		
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>

@endsection 

@section('footer-script')
<script src="{{ asset('js/barcodestock.js') }}" defer></script>
@endsection