@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Sale Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('sales.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Sale date:</strong>
								{{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y')}}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Invoice no:</strong>
								{{ $sale->invoice_no }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>PO no:</strong>
								{{ $sale->po_no }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Brand:</strong>
								{{ $sale->brand }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Category:</strong>
								{{ $sale->category }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Vendor type:</strong>
								{{ $sale->vendor_type }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Vendor name:</strong>
								{{ $sale->vendor_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Aggregator vendor name:</strong>
								{{ $sale->aggregator_vendor_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Hsn code:</strong>
								{{ $sale->hsn_code }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Sku code:</strong>
								{{ $sale->sku_code }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Product code:</strong>
								{{ $sale->product_code }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Colour:</strong>
								{{ $sale->colour }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Size:</strong>
								{{ $sale->size }}
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Quantity:</strong>
								{{ $sale->quantity }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Vendor Discount(%):</strong>
								{{ $sale->vendor_discount }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>MRP:</strong>
								{{ $sale->mrp }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Before tax amount:</strong>
								{{ $sale->before_tax_amount }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>State:</strong>
								{{ $sale->state }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>CGST:</strong>
								{{ $sale->cgst }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>SGST:</strong>
								{{ $sale->sgst }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>IGST:</strong>
								{{ $sale->igst }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Sale price:</strong>
								{{ $sale->sale_price }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Total sale amount:</strong>
								{{ $sale->total_sale_amount }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Cost price:</strong>
								{{ $sale->cost_price }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Total cost amount:</strong>
								{{ $sale->total_cost_amount }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Receivable amount:</strong>
								{{ $sale->receivable_amount }}
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Product image:</strong>
								<img src="{{ asset($sale->image_url) }}" class="img-thumbnail">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection