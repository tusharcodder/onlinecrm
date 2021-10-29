<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Type</th>
				<th>Vendor name</th>
				<th>Agg vendor</th>
				<th>Brand</th>
				<th>Category</th>
				<th>Gender</th>
				<th>Lotno</th>
				<th>Product code</th>
				<th>Closing stock</th>
				<th>Cost</th>
				<th>Online MRP</th>
				<th>Offline MRP</th>
				<th>Discount(%)</th>
				<th>Sale Through(%)</th>
				<th>Valid from date</th>
				<th>Valid to date</th>
				<th>Image</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $res)
				@php
					$res->quantity = empty($res->quantity) ? 0 : $res->quantity;
					$res->sale_qty = empty($res->sale_qty) ? 0 : $res->sale_qty;
					
					$salethrough = 0;
					if($res->quantity > 0 ){
						$salethrough = ($res->sale_qty * 100)/$res->quantity;
						$salethrough = number_format((float)$salethrough, 2, '.', '');
					}									
				@endphp	
								
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $res->vendor_type }}</td>
					<td>{{ $res->vendor_name }}</td>
					<td>{{ $res->aggregator_vendor_name }}</td>
					<td>{{ $res->brand}}</td>
					<td>{{ $res->category}}</td>
					<td>{{ $res->gender}}</td>
					<td>{{ $res->lotno}}</td>
					<td>{{ $res->product_code}}</td>
					<td>{{ $res->quantity - $res->sale_qty }}</td>
					<td>{{ $res->cost}}</td>
					<td>{{ $res->online_mrp}}</td>
					<td>{{ $res->offline_mrp}}</td>
					<td>{{ $res->discount }}%</td>
					<td>{{ $salethrough }}%</td>
					<td> {{ \Carbon\Carbon::parse($res->valid_from_date)->format('d-m-Y')}}</td>
					<td>{{ \Carbon\Carbon::parse($res->valid_to_date)->format('d-m-Y')}}</td>
					@if($exporttype != 'csv')
						<td>
							@if(!empty($res->img_url))
							<img src="{{ public_path($res->image_url) }}" width="50px"/>
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