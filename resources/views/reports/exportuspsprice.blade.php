<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
							
				<th>Tracing no</th>
                <th>Volumetic Weight</th>
				<th>price</th>
				<th>Shipping weight</th>
                <th>zone</th>
				<th>zone price</th>							
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $val)				
				<tr>
					<td>{{ $val['tracking_no'] }}</td>
                    <td>{{ $val['wgt'] }}</td>
                    <td>{{ $val['price'] }}</td>
                    <td>{{ $val['package_wgt'] }}</td>
                    <td>{{ $val['Zone'] }}</td>
					<td>{{ $val['zone_price'] }}</td>									
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>