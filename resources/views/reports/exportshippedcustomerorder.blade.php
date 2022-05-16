<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>isbn13</th>
				<th>sku</th>
				<th>product_name</th>
				<th>author</th>
				<th>publisher</th>
				<th>order_id</th>
				<th>order_item_id</th>
				<th>order_date</th>
				<th>quantity</th>
				<th>warehouse_id</th>
				<th>warehouse</th>
				<th>warehouse_country</th>
				<th>name</th>
				<th>recipent_name</th>
				<th>phone_number</th>
				<th>add1</th>
				<th>add2</th>
				<th>add3</th>
				<th>city</th>
				<th>state</th>
				<th>postal_code</th>
				<th>country</th>
				<th>market_place_acc</th>
				<th>ship_type</th>
				<th>wght</th>
				<th>listing_wgt</th>
				<th>price</th>
				<th>selling_price</th>
				<th>shipping_price</th>
				<th>shipper_id</th>
				<th>shipper_tracking_id</th>
				<th>box_id</th>
				<th>box_shipper_id</th>
				<th>shipment_date</th>
				<th>ncp</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $shipment)						
				<tr>
					<td>{{ ++$key }}</td>
					<td>"{{ $shipment->isbnno}}"</td>
					<td>{{ $shipment->sku}}</td>
					<td> {{ (!empty($shipment->proname)) ? $shipment->proname  :  $shipment->product_name}}</td>
					<td>{{ $shipment->author}}</td>
					<td>{{ $shipment->publisher}}</td>
					<td>{{ $shipment->order_id}}</td>
					<td>"{{ $shipment->order_item_id}}"</td>
					<td> {{ \Carbon\Carbon::parse($shipment->purchase_date)->format('d-m-Y')}}</td>
					<td>{{ $shipment->quantity_shipped }}</td>
					<td>{{ $shipment->wid}}</td>
					<td>{{ $shipment->wname}}</td>
					<td>{{ $shipment->warehouse_country_code}}</td>
					<td>{{ $shipment->buyer_name}}</td>
					<td>{{ $shipment->recipient_name }}</td>
					<td>{{ $shipment->buyer_phone_number }}</td>
					<td>{{ $shipment->ship_address_1}}</td>
					<td>{{ $shipment->ship_address_2}}</td>
					<td>{{ $shipment->ship_address_3 }}</td>
					<td>{{ $shipment->ship_city }}</td>
					<td>{{ $shipment->ship_state }}</td>
					<td>{{ $shipment->ship_postal_code }}</td>
					<td>{{ $shipment->ship_country }}</td>
					<td>{{ $shipment->markname }}</td>
					<td>{{ $shipment->ship_service_level }}</td>
					<td>{{ $shipment->wght }}</td>
					<td>{{ $shipment->pkg_wght }}</td>
					<td>{{ $shipment->price }}</td>
					<td>{{ $shipment->selling_price }}</td>
					<td>{{ $shipment->shipping_price }}</td>
					<td>{{ $shipment->shipper_name }}</td>
					<td>{{ $shipment->shipper_tracking_id }}</td>
					<td>{{ $shipment->box_id }}</td>
					<td>{{ $shipment->box_shipper_id }}</td>
					<td>{{ $shipment->shipment_date }}</td>
					<td>{{ $shipment->ncp }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>