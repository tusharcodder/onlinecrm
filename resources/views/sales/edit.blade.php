@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Edit Sale Item') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('sales.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
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
					
					<form method="POST" action="{{ route('sales.update', $sale->id) }}" id="saleform">
					@csrf
					@method('PATCH')						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="sale_date" class="col-form-label text-md-right">{{ __('Sale date*') }}</label>
									<input id="sale_date" type="date" class="form-control" name="sale_date" value="{{ old('sale_date', $sale->sale_date) }}"  autocomplete="sale_date" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="invoice_no" class="col-form-label text-md-right">{{ __('Invoice no*') }}</label>
									<input id="invoice_no" type="text" class="form-control" name="invoice_no" value="{{ old('invoice_no', $sale->invoice_no) }}"  autocomplete="invoice_no" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="po_no" class="col-form-label text-md-right">{{ __('PO no*') }}</label>
									<input id="po_no" type="text" class="form-control" name="po_no" value="{{ old('po_no', $sale->po_no) }}"  autocomplete="po_no" required/>
								</div>
							</div>
							
							
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_type" class="col-form-label text-md-right">{{ __('Vendor type*') }}</label>
									<input id="vendor_type" type="text" class="form-control" name="vendor_type" value="{{ old('vendor_type', $sale->vendor_type) }}" autocomplete="vendor_type" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor_name" class="col-form-label text-md-right">{{ __('Vendor name*') }}</label>
									<input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ old('vendor_name', $sale->vendor_name) }}" autocomplete="vendor_name" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="aggregator_vendor_name" class="col-form-label text-md-right">{{ __('Aggregator vendor name*') }}</label>
									<input id="aggregator_vendor_name" type="text" class="form-control" name="aggregator_vendor_name" value="{{ old('aggregator_vendor_name', $sale->aggregator_vendor_name) }}" autocomplete="aggregator_vendor_name"/>
								</div>
							</div>
						</div>
						
						<div class="row">

							<div class="col-md-3">
								<div class="form-group">
									<label for="hsn_code" class="col-form-label text-md-right">{{ __('Hsn code') }}</label>
									<input id="hsn_code" type="text" class="form-control" name="hsn_code" value="{{ old('hsn_code', $sale->hsn_code) }}"  autocomplete="hsn_code" />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="sku_code" class="col-form-label text-md-right">{{ __('Sku code*') }}</label>
									<input id="sku_code" type="text" class="form-control" name="sku_code" value="{{ old('sku_code', $sale->sku_code) }}"  autocomplete="sku_code" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="product_code" class="col-form-label text-md-right">{{ __('Product code*') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ old('product_code', $sale->product_code) }}"  autocomplete="product_code" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="brand" class="col-form-label text-md-right">{{ __('Brand*') }}</label>
									<input id="brand" type="text" class="form-control" name="brand" value="{{ old('brand', $sale->brand) }}"  autocomplete="brand" required/>
								</div>
							</div>
						</div>
						
						<div class="row">

							<div class="col-md-3">
								<div class="form-group">
									<label for="category" class="col-form-label text-md-right">{{ __('Category*') }}</label>
									<input id="category" type="text" class="form-control" name="category" value="{{ old('category', $sale->category) }}"  autocomplete="category" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="colour" class="col-form-label text-md-right">{{ __('Colour') }}</label>
									<input id="colour" type="text" class="form-control" name="colour" value="{{ old('colour', $sale->colour) }}"  autocomplete="colour"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="size" class="col-form-label text-md-right">{{ __('Size*') }}</label>
									<input id="size" type="text" class="form-control" name="size" value="{{ old('size', $sale->size) }}"  autocomplete="size" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="quantity" class="col-form-label text-md-right">{{ __('Quantity*') }}</label>
									<input id="quantity" type="number" class="form-control" name="quantity" value="{{ old('quantity', $sale->quantity) }}" step="any" autocomplete="quantity" required />
								</div>
							</div>
						</div>
						
						<div class="row">
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="mrp" class="col-form-label text-md-right">{{ __('Vendor Discount(%)') }}</label>
									<input id="vendiscount" type="number" class="form-control" name="vendiscount" value="{{ old('vendiscount',$sale->vendor_discount) }}" step="any" autocomplete="vendiscount"  />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="mrp" class="col-form-label text-md-right">{{ __('Mrp*') }}</label>
									<input id="mrp" type="number" class="form-control" name="mrp" value="{{ old('mrp', $sale->mrp) }}" step="any" autocomplete="mrp" required />
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="before_tax_amount" class="col-form-label text-md-right">{{ __('Before tax amount*') }}</label>
									<input id="before_tax_amount" type="number" class="form-control" name="before_tax_amount" value="{{ old('before_tax_amount', $sale->before_tax_amount) }}" step="any" autocomplete="before_tax_amount" required/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="state" class="col-form-label text-md-right">{{ __('State*') }}</label>
									<input id="state" type="text" class="form-control" name="state" value="{{ old('state', $sale->state) }}" autocomplete="state" required/>
								</div>
							</div>
							
							
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="igst" class="col-form-label text-md-right">{{ __('IGST') }}</label>
									<input id="igst" type="number" class="form-control" name="igst" value="{{ old('igst', $sale->igst) }}" step="any" autocomplete="igst"/>
								</div>
							</div>						
							<div class="col-md-3">
								<div class="form-group">
									<label for="sgst" class="col-form-label text-md-right">{{ __('SGST') }}</label>
									<input id="sgst" type="number" class="form-control" name="sgst" value="{{ old('sgst', $sale->sgst) }}" step="any" autocomplete="sgst"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="cgst" class="col-form-label text-md-right">{{ __('CGST') }}</label>
									<input id="cgst" type="number" class="form-control" name="cgst" value="{{ old('cgst', $sale->cgst) }}" step="any" autocomplete="cgst"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="sale_price" class="col-form-label text-md-right">{{ __('Sale price') }}</label>
									<input id="sale_price" type="number" class="form-control" name="sale_price" value="{{ old('sale_price', $sale->sale_price) }}" step="any" autocomplete="sale_price"/>
								</div>
							</div>
							
							
						</div>
						
						<div class="row">	
							<div class="col-md-3">
								<div class="form-group">
									<label for="total_sale_amount" class="col-form-label text-md-right">{{ __('Total sale amount') }}</label>
									<input id="total_sale_amount" type="number" class="form-control" name="total_sale_amount" value="{{ old('total_sale_amount', $sale->total_sale_amount) }}" step="any" autocomplete="total_sale_amount"/>
								</div>
							</div>						
							<div class="col-md-3">
								<div class="form-group">
									<label for="cost_price" class="col-form-label text-md-right">{{ __('Cost price') }}</label>
									<input id="cost_price" type="number" class="form-control" name="cost_price" value="{{ old('cost_price', $sale->cost_price) }}" step="any" autocomplete="cost_price"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="total_cost_amount" class="col-form-label text-md-right">{{ __('Total cost amount') }}</label>
									<input id="total_cost_amount" type="number" class="form-control" name="total_cost_amount" value="{{ old('total_cost_amount', $sale->total_cost_amount) }}" step="any" autocomplete="total_cost_amount"/>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="receivable_amount" class="col-form-label text-md-right">{{ __('Receivable amount*') }}</label>
									<input id="receivable_amount" type="number" class="form-control" name="receivable_amount" value="{{ old('receivable_amount', $sale->receivable_amount) }}" step="any" autocomplete="receivable_amount" required/>
								</div>
							</div>
							
						</div>
					
						<div class="form-group row mb-0">
                            <div class="col-md-12">
								<button type="submit" id="save_stock" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/sale.js') }}" defer></script>
@endsection