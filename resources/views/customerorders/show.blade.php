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
								{{ $customerorders->order_id }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Order item id:</strong><br/>
								{{ $customerorders->order_item_id }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Purchase date:</strong><br/>
								{{ \Carbon\Carbon::parse($customerorders->purchase_date)->format('d-m-Y H:m:s')}}
							</div>
						</div>
						
					</div>				
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Payment date:</strong><br/>
								{{ \Carbon\Carbon::parse($customerorders->payments_date)->format('d-m-Y H:m:s')}}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Reporting date:</strong><br/>
								{{ \Carbon\Carbon::parse($customerorders->reporting_date)->format('d-m-Y H:m:s')}}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Promise date:</strong><br/>
								{{ \Carbon\Carbon::parse($customerorders->promise_date)->format('d-m-Y H:m:s')}}
							</div>
						</div>
                        
					</div>	
                    <div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Days past promise:</strong><br/>
								{{ $customerorders->days_past_promise }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Buyer name:</strong><br/>
								{{ $customerorders->buyer_name }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Buyer phone number:</strong><br/>
								{{ $customerorders->buyer_phone_number }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Buyer email:</strong><br/>
								{{ $customerorders->buyer_email }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Sku:</strong><br/>
								{{ $customerorders->sku }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Product name:</strong><br/>
								{{ $customerorders->product_name }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Quantity purchased:</strong><br/>
								{{ $customerorders->quantity_purchased }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Quantity shipped:</strong><br/>
								{{ $customerorders->quantity_shipped }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Quantity to ship:</strong><br/>
								{{ $customerorders->quantity_to_ship }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship service level:</strong><br/>
								{{ $customerorders->ship_service_level }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Recipient name:</strong><br/>
								{{ $customerorders->recipient_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship address 1:</strong><br/>
								{{ $customerorders->ship_address_1 }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship address 2:</strong><br/>
								{{ $customerorders->ship_address_2 }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship address 3:</strong><br/>
								{{ $customerorders->ship_address_3 }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship city:</strong><br/>
								{{ $customerorders->ship_city }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship state:</strong><br/>
								{{ $customerorders->ship_state }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship postal code:</strong><br/>
								{{ $customerorders->ship_postal_code }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Ship country:</strong><br/>
								{{ $customerorders->ship_country }}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Is business order:</strong><br/>
								{{ $customerorders->is_business_order }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Purchase order number:</strong><br/>
								{{ $customerorders->purchase_order_number }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Price designation:</strong><br/>
								{{ $customerorders->price_designation }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection