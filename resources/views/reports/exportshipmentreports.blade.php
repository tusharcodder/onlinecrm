<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Isbn13</th>
				<th>Sku</th>
				<th>Product_name</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Order_id</th>
				<th>Order_item_id</th>
				<th>Order_date</th>
				<th>Quantity</th>
				<th>Warehouse</th>
				<th>Name</th>
				<th>Recipent_name</th>
				<th>Phone_number</th>
				<th>Add1</th>
				<th>Add2</th>
				<th>Add3</th>
				<th>City</th>
				<th>State</th>
				<th>Postal_code</th>
				<th>Country</th>
				<th>MarPla_acc</th>
				<th>Ship_type</th>
				<th>Listing_wgt</th>
				<th>price</th>
				<th>shipper</th>
				<th>tracking_id</th>
				<th>box_id</th>
				<th>shipper_id</th>
				<th>shipment_date</th>
				<th>ncp</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $shipment)
				@php
					$shipment->shipingqty = empty($shipment->shipingqty) ? 0 : $shipment->shipingqty;
				@endphp			
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $shipment->isbnno}}</td>
					<td>{{ $shipment->sku}}</td>
					<td>{{ $shipment->proname}}</td>
					<td>{{ $shipment->author}}</td>
					<td>{{ $shipment->publisher}}</td>
					<td>{{ $shipment->order_id}}</td>
					<td>{{ $shipment->order_item_id}}</td>
					<td> {{ \Carbon\Carbon::parse($shipment->purchase_date)->format('d-m-Y')}}</td>
					<td>{{ $shipment->shipingqty }}</td>
					<td>{{ $shipment->warename}}</td>
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
					<td>{{ $shipment->pkg_wght }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>