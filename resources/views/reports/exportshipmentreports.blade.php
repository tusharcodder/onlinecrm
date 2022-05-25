<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>isbn13</th>
				<th>bisbn</th>
				<th>sku</th>
				<th>product_name</th>
				<th>books_isbn</th>
				<th>author</th>
				<th>publisher</th>
				<th>order_id</th>
				<th>order_item_id</th>
				<th>order_date</th>
				<th>quantity</th>
				<th>warehouse_id</th>
				<th>warehouse</th>
				<th>warehouse_country</th>
				<th>rack_details</th>
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
				<th>ounce</th>
				<th>Mrp</th>
				<th>price</th>
				<th>selling_price</th>
				<th>shipping_price</th>
				<th>shipper_id</th>
				<th>shipper_tracking_id</th>
				<th>box_id</th>
				<th>box_shipper_id</th>
				<th>shipment_date</th>
				<th>ncp</th>				
				<th>Label Pdf Url</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $shipment)
				@php
					$shipment->shipingqty = empty($shipment->shipingqty) ? 0 : $shipment->shipingqty;
				@endphp			
				<tr>
					<td>{{ ++$key }}</td>
					<td>"{{ $shipment->isbnno}}"</td>
					<td>"{{ $shipment->shipper_book_isbn}}"</td>
					<td>{{ $shipment->sku}}</td>
					<td> {{ (!empty($shipment->proname)) ? $shipment->proname  :  $shipment->product_name}}</td>
					<td>"{{ $shipment->shipper_book_isbn}}"</td>
					<td>{{ $shipment->author}}</td>
					<td>{{ $shipment->publisher}}</td>
					<td>{{ $shipment->order_id}}</td>
					<td>"{{ $shipment->order_item_id}}"</td>
					<td> {{ \Carbon\Carbon::parse($shipment->purchase_date)->format('d-m-Y')}}</td>
					<td>{{ $shipment->shipingqty }}</td>
					<td>{{ $shipment->warehouse_id}}</td>
					<td>{{ $shipment->warehouse_name}}</td>
					<td>{{ $shipment->warehouse_country_code}}</td>
					<td>{{ $shipment->warehouse_rack_details}}</td>
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
					<td>{{ $shipment->oz_wt }}</td>
					<td>{{ $shipment->mrp }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>{{ $shipment->tracking_number }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>{{ $shipment->label_pdf_url }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>