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
					if($val->market_qunatity < 10)
						$quantity = (0 + $val->stock_qty);
					else if($val->market_qunatity < 15)
						$quantity = (2 + $val->stock_qty);	
					else if($val->market_qunatity < 25)
						$quantity = (3 + $val->stock_qty);	
					else if($val->market_qunatity < 50)
						$quantity = (10 + $val->stock_qty);	
					else if($val->market_qunatity < 100)
						$quantity = (25 + $val->stock_qty);	
					else
						$quantity = (40 + $val->stock_qty);
				@endphp			
				<tr>
					<td>{{ $val->sku }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td>{{ $quantity }}</td>
					<td>{{ $val->leadtime }}</td>
					<td></td>
					<td>{{$val->market_qunatity}} </td>
					<td>{{$val->stock_qty}}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>