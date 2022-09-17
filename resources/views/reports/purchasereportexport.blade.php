<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>				
				<th>Sku</th>
				<th>Isbn 13</th>
				<th>Child Isbn</th>
				<th>Book Name</th>
				<th>Mrp</th>
				<th>Author</th>
				<th>Publisher</th>
				<th>Require quantity</th>
				<th>Vendor quantity</th>
				<th>Vendor Name</th>
				<th>Vendor Data</th>						
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res['Sku'] }}</td>
					<td >{{ $res['isbn13'] }}</td>
					<td >{{ $res['cisbn13'] }}</td>
					<td >{{ $res['book'] }}</td>
					<td >{{ $res['mrp'] }}</td>
					<td >{{ $res['author'] }}</td>
					<td >{{ $res['publisher'] }}</td>
					<td >{{ $res['New'] }}</td>
					<td >{{ $res['quantity'] }}</td>
					<td >{{ $res['vendor_name'] }}</td>	
					<td >{{ $res['vendordata'] }}</td>					
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>