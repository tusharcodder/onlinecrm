<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
							
				<th>sku</th>
				<th>price</th>
				<th>minimum-seller-allowed-price</th>
				<th>maximum-seller-allowed-price</th>
				<th>quantity</th>
				<th>leadtime-to-ship</th>
				<th>fulfillment-channel</th>				
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $val)
				@php
					$quantity = ($val->market_qunatity + $val->stock_qty);
				@endphp			
				<tr>
					<td>{{ $val->sku }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td>{{ $quantity }}</td>
					<td>{{ $val->leadtime }}</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>