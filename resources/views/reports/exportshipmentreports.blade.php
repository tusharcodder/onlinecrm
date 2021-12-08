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
				<th>warehouse</th>
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
				<th>marPla_acc</th>
				<th>ship_type</th>
				<th>listing_wgt</th>
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