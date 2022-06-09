<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>order_id</th>
				<th>order_item_id</th>
				<th>ship_date</th>
				<th>quantity</th>
				<th>carrier_code</th>
				<th>carrier_name</th>
				<th>tracking_number</th>
				<th>ship_method</th>
				<th>transparency_code</th>
				<th>ship_from_address_name</th>
				<th>ship_from_address_line1</th>
				<th>ship_from_address_line2</th>
				<th>ship_from_address_line3</th>
				<th>ship_from_address_city</th>
				<th>ship_from_address_county</th>
				<th>ship_from_address_state_or_region</th>
				<th>ship_from_address_postalcode</th>
				<th>ship_from_address_countrycode</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $shipment)						
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $shipment->order_id }}</td>
					<td>"{{ $shipment->order_item_id }}"</td>
					<td>{{ $shipment->shipment_date }}</td>
					<td>{{ $shipment->quantity_shipped }}</td>
					<td>{{ $shipment->carrier_service }}</td>
					<td>{{ $shipment->carrier_name }}</td>
					<td>"{{ $shipment->shipper_tracking_id }}"</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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