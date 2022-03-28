<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Market_Place</th>
				<!--<th>Warehouse</th>-->
				<th>isbn13</th>
				<th>isbn10</th>
				<th>sku_code</th>
				<th>mrp</th>
				<th>disc</th>
				<th>weight(kg)</th>
				<th>ounces_wt</th>
				<th>Type</th>				
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>					
					<td >{{ $res->Market_Place }}</td>
					<!--<td >{{ $res->Warehouse }}</td>-->
					<td >{{ $res->isbn13 }}</td>
					<td >{{ $res->isbn10 }}</td>
					<td >{{ $res->sku_code }}</td>
					<td >{{ $res->mrp }}</td>
					<td >{{ $res->disc }}</td>
					<td >{{ $res->wght }}</td>
					<td >{{ $res->oz_wt }}</td>	
					<td >{{ $res->type }}</td>				
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>