<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>manufacturer_name</th>
				<th>country</th>
				<th>manufacture_date</th>
				<th>cost</th>
				<th>stock_date</th>
				<th>brand</th>
				<th>category</th>
				<th>gender</th>
				<th>colour</th>
				<th>size</th>
				<th>lotno</th>
				<th>sku_code</th>
				<th>product_code</th>
				<th>hsn_code</th>
				<th>online_mrp</th>
				<th>offline_mrp</th>
				<th>quantity</th>
				<th>description</th>
				<th>product_image</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res->manufacturer_name }}</td>
					<td >{{ $res->country }}</td>
					<td >{{ \Carbon\Carbon::parse($res->manufacture_date)->format('d-m-Y') }}</td>
					<td >{{ $res->cost }}</td>
					<td >{{ \Carbon\Carbon::parse($res->stock_date)->format('d-m-Y') }}</td>
					<td >{{ $res->brand }}</td>
					<td >{{ $res->category }}</td>
					<td >{{ $res->gender }}</td>
					<td >{{ $res->colour }}</td>
					<td >{{ $res->size }}</td>
					<td >{{ $res->lotno }}</td>
					<td >{{ $res->sku_code }}</td>
					<td >{{ $res->product_code }}</td>
					<td >{{ $res->hsn_code }}</td>
					<td >{{ $res->online_mrp }}</td>
					<td >{{ $res->offline_mrp }}</td>
					<td >{{ $res->quantity }}</td>
					<td >{{ $res->description }}</td>
					@if($exporttype != 'csv')
						<td>
						@if(!empty($res->img_url))
							<img src="{{ public_path($res->img_url) }}" width="50px"/>
						@endif
						</td>
					@else
						<td>{{ asset($res->img_url) }}</td>
					@endif
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>