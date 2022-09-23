<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>order_id</th>
				<th>order_item_id</th>
				<th>purchase_date</th>
				<th>payments_date</th>
				<th>reporting_date</th>
				<th>promise_date</th>
				<th>days_past_promise</th>
				<th>buyer_email</th>
				<th>buyer_name</th>
				<th>buyer_phone_number</th>
				<th>sku</th>
				<th>product_name</th>
				<th>quantity_purchased</th>
				<th>quantity_shipped</th>
				<th>quantity_to_ship</th>
				<th>ship_service_level</th>
				<th>recipient_name</th>
				<th>ship_address_1</th>
				<th>ship_address_2</th>
				<th>ship_address_3</th>
				<th>ship_city</th>
				<th>ship_state</th>
				<th>ship_postal_code</th>
				<th>ship_country</th>
				<th>is_business_order</th>
				<th>purchase_order_number</th>
				<th>price_designation</th>
				<th>currency</th>
				<th>item_price</th>
				<th>item_tax</th>
				<th>sales_channel</th>
				<th>earliest_ship_date</th>
				<th>latest_ship_date</th>
				<th>earliest_delivery_date</th>
				<th>latest_delivery_date</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res->order_id }}</td>
					<td >{{ $res->order_item_id }}</td>
					<td >{{ $res->purchase_date }}</td>
					<td >{{ $res->payments_date }}</td>
					<td >{{ $res->reporting_date }}</td>
					<td >{{ $res->promise_date }}</td>
					<td >{{ $res->days_past_promise }}</td>
					<td >{{ $res->buyer_email }}</td>
					<td >{{ $res->buyer_name }}</td>
					<td >{{ $res->buyer_phone_number }}</td>
					<td >{{ $res->sku }}</td>
					<td >{{ $res->product_name }}</td>
					<td >{{ $res->quantity_purchased }}</td>
					<td >{{ $res->quantity_shipped }}</td>
					<td >{{ $res->quantity_to_ship }}</td>
					<td >{{ $res->ship_service_level }}</td>
					<td >{{ $res->recipient_name }}</td>
					<td >{{ $res->ship_address_1 }}</td>
					<td >{{ $res->ship_address_2 }}</td>
					<td >{{ $res->ship_address_3 }}</td>
					<td >{{ $res->ship_city }}</td>
					<td >{{ $res->ship_state }}</td>
					<td >{{ $res->ship_postal_code }}</td>
					<td >{{ $res->ship_country }}</td>
					<td >{{ $res->is_business_order }}</td>
					<td >{{ $res->purchase_order_number }}</td>
					<td >{{ $res->price_designation }}</td>
					<td >{{ $res->currency }}</td>
					<td >{{ $res->item_price }}</td>
					<td >{{ $res->item_tax }}</td>
					<td >{{ $res->sales_channel }}</td>
					<td >{{ $res->earliest_ship_date }}</td>
					<td >{{ $res->latest_ship_date }}</td>
					<td >{{ $res->earliest_delivery_date }}</td>
					<td >{{ $res->latest_delivery_date }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>