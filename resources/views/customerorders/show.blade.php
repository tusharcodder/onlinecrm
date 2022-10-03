@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Customer Order Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('customerorders.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>				
                <div class="card-body">
					<div class="row">					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Order id:</strong><br/>
								{{ $customerorders[0]->cust_order_id }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Order item id:</strong><br/>
								{{ $customerorders[0]->cust_order_item_id }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Purchase Date:</strong><br/>
								@if (!empty($customerorders[0]->purchase_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->purchase_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
						
					</div>				
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Payment Date:</strong><br/>
								@if (!empty($customerorders[0]->payments_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->payments_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Reporting Date:</strong><br/>
								@if (!empty($customerorders[0]->reporting_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->reporting_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Promise Date:</strong><br/>
								@if (!empty($customerorders[0]->promise_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->promise_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
                        
					</div>	
                    <div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Days past promise:</strong><br/>
								{{ $customerorders[0]->days_past_promise }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Buyer name:</strong><br/>
								{{ $customerorders[0]->buyer_name }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Buyer phone number:</strong><br/>
								{{ $customerorders[0]->buyer_phone_number }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Buyer email:</strong><br/>
								{{ $customerorders[0]->buyer_email }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Sku:</strong><br/>
								{{ $customerorders[0]->sku }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Product name:</strong><br/>
								{{ $customerorders[0]->product_name }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Quantity purchased:</strong><br/>
								{{ $customerorders[0]->quantity_purchased }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Quantity shipped:</strong><br/>
								{{ $customerorders[0]->quantity_shipped }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Quantity to ship:</strong><br/>
								{{ $customerorders[0]->quantity_to_ship }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship service level:</strong><br/>
								{{ $customerorders[0]->ship_service_level }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Recipient name:</strong><br/>
								{{ $customerorders[0]->recipient_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship address 1:</strong><br/>
								{{ $customerorders[0]->ship_address_1 }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship address 2:</strong><br/>
								{{ $customerorders[0]->ship_address_2 }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship address 3:</strong><br/>
								{{ $customerorders[0]->ship_address_3 }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship city:</strong><br/>
								{{ $customerorders[0]->ship_city }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship state:</strong><br/>
								{{ $customerorders[0]->ship_state }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship postal code:</strong><br/>
								{{ $customerorders[0]->ship_postal_code }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship country:</strong><br/>
								{{ $customerorders[0]->ship_country }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Is business order:</strong><br/>
								{{ $customerorders[0]->is_business_order }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Purchase order number:</strong><br/>
								{{ $customerorders[0]->purchase_order_number }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Price designation:</strong><br/>
								{{ $customerorders[0]->price_designation }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Box Shipper Id:</strong><br/>
								{{ $customerorders[0]->box_shipper_id }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Warehouse:</strong><br/>
								{{ $customerorders[0]->warehouse_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Selling Price:</strong><br/>
								{{ $customerorders[0]->selling_price }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Shipping Price:</strong><br/>
								{{ $customerorders[0]->shipping_price }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Shipping Tracking Id:</strong><br/>
								{{ $customerorders[0]->shipper_tracking_id }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Box Id:</strong><br/>
								{{ $customerorders[0]->box_id }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Shipping Date:</strong><br/>
								@if (!empty($customerorders[0]->shipment_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->shipment_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Shipped Quantity:</strong><br/>
								{{ $customerorders[0]->quantity_shipped }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>NCP:</strong><br/>
								{{ $customerorders[0]->ncp }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Currency:</strong><br/>
								{{ $customerorders[0]->currency }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Item Price:</strong><br/>
								{{ $customerorders[0]->item_price }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Item Tax:</strong><br/>
								{{ $customerorders[0]->item_tax }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Sales Channel:</strong><br/>
								{{ $customerorders[0]->sales_channel }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Earliest Ship Date:</strong><br/>
								@if (!empty($customerorders[0]->earliest_ship_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->earliest_ship_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Latest Ship Date:</strong><br/>
								@if (!empty($customerorders[0]->latest_ship_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->latest_ship_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Earliest Delivery Date:</strong><br/>
								@if (!empty($customerorders[0]->earliest_delivery_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->earliest_delivery_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Latest Delivery Date:</strong><br/>
								@if (!empty($customerorders[0]->latest_delivery_date))
								{{ \Carbon\Carbon::parse($customerorders[0]->latest_delivery_date)->format('d-m-Y H:m:s')}}
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Shipping Tracking Status:</strong><br/>
								{{ $customerorders[0]->tracking_message }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection