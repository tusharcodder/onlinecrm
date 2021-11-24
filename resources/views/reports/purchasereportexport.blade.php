<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>isbn13</th>
				<th>Book Name</th>
				<th>Author</th>
                <th>publisher</th>
				<th>Quantity</th>
				<th>Vendor</th>							
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res['isbn13'] }}</td>
					<td >{{ $res['book'] }}</td>
					<td >{{ $res['author'] }}</td>
					<td >{{ $res['publisher'] }}</td>
					<td >{{ $res['quantity'] }}</td>
					<td >{{ $res['vendor_name'] }}</td>						
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>