<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Brand</th>
				<th>Category</th>
				<th>Gender</th>
				<th>Colour</th>
				<!--<th>Size</th>-->
				<th>Lotno</th>
				<!--<th>Sku code</th>-->
				<th>Product code</th>
				<th>Hsn code</th>
				<th>Opening stock</th>
				<th>Closing stock</th>
				<th>Sale Through(%)</th>
				<th>Performance</th>
				<th>Image</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $res)
				@php
					$res->quantity = empty($res->quantity) ? 0 : $res->quantity;
					$res->closing_qty = empty($res->closing_qty) ? 0 : $res->closing_qty;
					$res->sale_qty = empty($res->sale_qty) ? 0 : $res->sale_qty;
					$res->net_sale_qty = empty($res->net_sale_qty) ? 0 : $res->net_sale_qty;
					
					$salethrough = 0;
					if($res->closing_qty > 0 ){
						$salethrough = ($res->sale_qty * 100)/$res->closing_qty;
						$salethrough = number_format((float)$salethrough, 2, '.', '');
					}
				@endphp
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $res->brand}}</td>
					<td>{{ $res->category}}</td>
					<td>{{ $res->gender}}</td>
					<td>{{ $res->colour}}</td>
					<!--<td>{{ $res->size}}</td>-->
					<td>{{ $res->lotno}}</td>
					<!--<td>{{ $res->sku_code}}</td>-->
					<td>{{ $res->product_code}}</td>
					<td>{{ $res->hsn_code}}</td>
					<td>{{ $res->quantity}}</td>
					<td>{{ $res->quantity - $res->net_sale_qty }}</td>
					<td>{{ $salethrough}}%</td>
					<td>{{ $res->performance}}</td>
					@if($exporttype != 'csv')
						<td>
							@if(!empty($res->img_url))
							<img src="{{ public_path($res->img_url) }}" width="50px"/>
							@endif
						</td>
					@else
						<td>{{ asset($res->image_url) }}</td>
					@endif
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>