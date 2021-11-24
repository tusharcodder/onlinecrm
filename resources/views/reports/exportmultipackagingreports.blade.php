<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Isbn 13</th>
				<th>Sku</th>
				<th>Title</th>
				<th>Order ID</th>
				<th>Order Item ID</th>
				<th>Order Date</th>
				<th>Quantity</th>
				<th>Country</th>
				<th>Location</th>
				<th>Label No</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $multipack)
				@php
					$multipack->shipingqty = empty($multipack->shipingqty) ? 0 : $multipack->shipingqty;
				@endphp			
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $multipack->isbnno }}</td>
					<td>{{ $multipack->sku }}</td>
					<td>{{ $multipack->bookname }}</td>
					<td>{{ $multipack->order_id }}</td>
					<td>{{ $multipack->order_item_id }}</td>
					<td>{{ $multipack->purchase_date }}</td>
					<td>{{ $multipack->shipingqty }}</td>
					<td>{{ $multipack->ship_country }}</td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>