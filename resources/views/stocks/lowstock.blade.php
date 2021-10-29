<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Brand</th>
				<th>Category</th>
				<th>Gender</th>
				<th>Colour</th>
				<th>Size</th>
				<th>Lotno</th>
				<th>Sku_code</th>
				<th>Product code</th>
				<th>Hsn_code</th>
				<th>Quantity</th>
				<th>Product_image</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res->brand }}</td>
					<td >{{ $res->category }}</td>
					<td >{{ $res->gender }}</td>
					<td >{{ $res->colour }}</td>
					<td >{{ $res->size }}</td>
					<td >{{ $res->lotno }}</td>
					<td >{{ $res->sku_code }}</td>
					<td >{{ $res->product_code }}</td>
					<td >{{ $res->hsn_code }}</td>
					<td >{{ $res->quantity - $res->sale_qty }}</td>
					@if(file_exists(public_path($res->img_url)) && !empty($res->img_url))
						<td><img src="{{ public_path($res->img_url) }}" width="50px"/></td>
					@else
						<td><img src="{{ public_path('assets/assets/img/NA_image.jpg')}}" width="50px"/></td>
					@endif
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>